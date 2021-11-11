<?php
class RadioSystem extends BaseDBObject
{
	const DB_FIELDS = [
		'shortName',
		'talkgroupsFile',
	];
	const DB_KEY = 'shortName'; // lol this sucks

	//const DB_TABLE = '??';

	public function getTalkGroups(): iterable
	{
		$return = [];
		foreach (explode("\n", file_get_contents($this['talkgroupsFile'])) as $tgline)
		{
			$tgline = trim($tgline);
			// == 0 is correct - we want to check if the line starts with it
			if ($tgline == '' || stripos($tgline, 'Decimal') === 0)
			{
				continue;
			}

			$talkgroup = TalkGroup::createFromCSVLine($tgline);
			$return[$talkgroup['TGID']] = $talkgroup;
		}
		return $return;
	}

	public function getCalls(): iterable
	{
		global $config_json;

		$calls = [];
		$directory = new RecursiveDirectoryIterator($config_json['captureDir'].'/'.$this['shortName']);
		$iterator = new RecursiveIteratorIterator($directory);
		foreach ($iterator as $file)
		{
			if ($file->getExtension() != RadioCall::FILE_TYPE)
			{
				continue;
			}

			// parse filename to retrieve info about the call
			$basename = $file->getBaseName('.'.$FileType);
			[$TGID, $TIME, $FREQ] = preg_split('/[-_]/', $basename);

			if ($file->getSize() < 1024)
			{
				// Filtered because they will produce an error when attempting playback.
				continue;
			}

			$params = [
				'absolute_path' => $file->getPath().'/'.$file->getFileName(),
				'size_kb'       => round($file->getSize() / 1024),
				'TGID'          => $TGID,
				'unix_date'     => $TIME,
				'frequency'     => $FREQ,
			];

			$calls[] = new RadioCall(['record' => $params]);
		}
		return $calls;
	}

	public static function getAll(): iterable
	{
		global $config_json;

		$return = [];
		foreach ($config_json['systems'] as $system)
		{
			$record = [
				'shortName'      => $system['shortName'],
				'talkgroupsFile' => $system['talkgroupsFile'],
			];
			$return[] = new RadioSystem(['record' => $record]);
		}

		return $return;
	}
}
