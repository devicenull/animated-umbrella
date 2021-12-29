<?php
class RadioSystem extends BaseDBObject
{
	const DB_FIELDS = [
		'SYSTEMID',
		'name',
		'last_talkgroup_update',
		'talkgroup_path',
	];
	const DB_KEY = 'SYSTEMID';
	const DB_TABLE = 'radio_system';

	public function getTalkGroups(): iterable
	{
		//FIXME: should be under TalkGroup?
		global $db;
		$res = $db->Execute('
			select *
			from talk_group
			where SYSTEMID=?
			order by TGID DESC
			', [
				$this['SYSTEMID']
			],
		);
		$tgs = [];
		foreach ($res as $cur)
		{
			$tgs[] = new TalkGroup(['record' => $cur]);
		}

		return $tgs;
	}

	/**
	*	parse talkgroup config file, add/update talkgroups as necessary
	*/
	public function refreshTalkGroups(): void
	{
		foreach (explode("\n", file_get_contents($this['talkgroup_path'])) as $tgline)
		{
			// Decimal,Hex,Alpha Tag,Mode,Description,Tag,Category
			$tgline = trim($tgline);
			// == 0 is correct - we want to check if the line starts with it
			if ($tgline == '' || stripos($tgline, 'Decimal') === 0)
			{
				continue;
			}

			$columns = explode(',', $tgline);
			$params = [
				'SYSTEMID'     => $this['SYSTEMID'],
				'TGID'         => $columns[0],
				// TGID_hex $csv_line[1] (unused)
				'alpha_tag'    => $columns[2],
				'mode'         => $columns[3],
				'description'  => $columns[4],
				'tag'          => $columns[5],
				'category'     => $columns[6],
			];

			$tg = new TalkGroup([
				'SYSTEMID' => $this['SYSTEMID'],
				'TGID'     => $params['TGID'],
			]);
			if ($tg->isInitialized())
			{
				unset($params['TGID']);
				unset($params['SYSTEMID']);

				$tg->set($params);
			}
			else
			{
				$tg->add($params);
			}
		}

		$this->set(['last_talkgroup_update' => strftime('%F %T')]);
	}

	public function getCalls(string $filter_type='include', iterable $filter_tgids=[], int $since=0, int $limit=100): iterable
	{
		global $db;

		$sql = 'select * from radio_call where SYSTEMID=?';
		$params = [$this['SYSTEMID']];
		if (!empty($filter_tgids))
		{
			$query_extra = '';
			if ($filter_type == 'exclude') $query_extra = ' NOT ';
			$sql .= ' and TGID '.$query_extra.' in ('.implode(',', array_map('intVal', $filter_tgids)).')';
		}
		if ($since != 0)
		{
			$sql .= 'and call_date > ?';
			$params[] = strftime('%F %T', $since);
		}

		$sql .= ' order by call_date asc';
		$sql .= ' limit '.intVal($limit);
		$res = $db->Execute($sql, $params);
		$calls = [];
		foreach ($res as $cur)
		{
			$calls[] = new RadioCall(['record' => $cur]);
		}
		return $calls;
	}

	public static function getAll(): iterable
	{
		global $db;
		$res = $db->Execute('
			select *
			from radio_system
			order by SYSTEMID DESC
		');
		$systems = [];
		foreach ($res as $cur)
		{
			$systems[] = new RadioSystem(['record' => $cur]);
		}

		return $systems;
	}
}
