<?php
require(__DIR__.'/../init.php');

if (isset($_REQUEST['since']))
{
	$filter_date = (isset($_GET['date'])) ? new DateTimeImmutable($_GET['date']) : new DateTimeImmutable();
	$filter_tg = (empty($_GET['tg'])) ? null : $_GET['tg'];

	$latest_file = 0;

	$json = [];
	foreach (RadioSystem::getAll() as $system)
	{
		foreach ($system->getCalls() as $call)
		{
			$curcall = $call->getPublicData();
			$json[] = $curcall;
			if ($curcall['unix_date'] > $latest_file)
			{
				$latest_file = $curcall['unix_date'];
			}
		}
	}
	$json = array_slice($json, -100);
	//ksort($files);
	Header('Content-Type: application/json');
	echo json_encode([
		'latest'   => strtotime($latest_file),
		'newfiles' => $json,
	]);
	exit();
}

$params = [
	'current_date' => strftime('%F'),
	'all_systems'  => RadioSystem::getAll(),
];

displayPage('index.html', $params);
