<?php
class TalkGroup extends BaseDBObject
{
	const DB_FIELDS = [
		// FIXME: add support for multiple columns in DB_KEY and drop this
		'our_talkgroupid',
		'SYSTEMID',
		'TGID',
		'alpha_tag',
		'mode',
		'description',
		// yep, could be another table, but for a few hundred rows, who cares?
		'category',
	];
	const DB_KEY = 'our_talkgroupid';
	const DB_TABLE = 'talk_group';

	public function __construct($params=[])
	{
		global $db;
		if (isset($params['SYSTEMID']) && isset($params['TGID']))
		{
			$res = $db->Execute('select * from talk_group where SYSTEMID=? and TGID=?', [$params['SYSTEMID'], $params['TGID']]);
			if ($res->RecordCount() > 0)
			{
				$this->record = $res->fields;
			}
		}

		parent::__construct($params);
	}

	public function getDescription(): string
	{
		return $this['alpha_tag'].' ('.$this['TGID'].')';
	}
}
