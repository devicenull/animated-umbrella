<?php
class RadioCall extends BaseDBObject
{
	const DB_FIELDS = [
		'absolute_path',
		'size_kb',
		'TGID',
		'unix_date',
		'frequency',
	];
	const DB_KEY = 'absolute_path';
	
	//const DB_TABLE = '??';

	const FILE_TYPE = 'm4a';

	var $virtual_fields = [
		'date',
	];

	public function getHTTPPath(): string
	{
		$http_path = $this['absolute_path'];
		if (HTTP_BASE_PATH != '')
		{
			$http_path = str_ireplace(HTTP_BASE_PATH, '', $http_path);
		}
		return $http_path;
	}

	public function get($key): string
	{
		if ($key == 'date')
		{
			return strftime('%F %T', $this['unix_date']);
		}

		return parent::get($key);
	}

	public function getPublicData(): array
	{
		return [
			'path'      => $this->getHTTPPath(),
			'size_kb'   => $this['size_kb'],
			'talkgroup' => $this['TGID'],
			'unix_date' => $this['date'],
			'date'      => strftime('%F %T', $this['unix_date']),
			'frequency' => ($this['frequency'] / 1000000),
		];
	}
}
