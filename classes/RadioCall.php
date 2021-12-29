<?php
class RadioCall extends BaseDBObject
{
	const DB_FIELDS = [
		'CALLID',
		'absolute_path',
		'size_kb',
		'TGID',
		'call_date',
		'frequency',
		'SYSTEMID',
	];
	const DB_KEY = 'CALLID';
	const DB_TABLE = 'radio_call';

	const FILE_TYPE = 'm4a';

	var $virtual_fields = [
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

	public function getPublicData(): array
	{
		$tg = new TalkGroup([
			'SYSTEMID' => $this['SYSTEMID'],
			'TGID'     => $this['TGID'],
		]);
		if ($tg && $tg->isInitialized())
		{
			$description = $tg->getDescription();
		}
		else
		{
			$description = $this['TGID'];
		}
		return [
			'path'      => $this->getHTTPPath(),
			'size_kb'   => $this['size_kb'],
			'talkgroup' => $description,
			'call_date' => $this['call_date'],
			'frequency' => ($this['frequency'] / 1000000),
		];
	}
}
