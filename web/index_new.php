<?php
require(__DIR__.'/../init.php');

if (isset($_REQUEST['since']))
{
	$filter_date = (isset($_GET['date'])) ? new DateTimeImmutable($_GET['date']) : new DateTimeImmutable();
	$filter_tg = (empty($_GET['tg'])) ? null : $_GET['tg'];

	$latest_file = 0;

	$newcalls = [];
	foreach (RadioSystem::getAll() as $system)
	{
		foreach ($system->getCalls() as $call)
		{
			$curcall = $call->getPublicData();
			$newcalls[] = $curcall;
			if ($curcall['unix_date'] > $latest_file)
			{
				$latest_file = $curcall['unix_date'];
			}
		}
	}
	usort($newcalls, function ($a, $b)
	{
		if ($a['unix_date'] == $b['unix_date']) return 0;
		return ($a['unix_date'] < $b['unix_date']) ? -1 : 1;
	});

	$newcalls = array_slice($newcalls, -100);
	Header('Content-Type: application/json');
	echo json_encode([
		'latest'   => strtotime($latest_file),
		'newfiles' => $newcalls,
	]);
	exit();
}

$params = [
	'current_date' => strftime('%F'),
	'all_systems'  => RadioSystem::getAll(),
];

displayPage('index.html', $params);
