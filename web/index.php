<?php
require(__DIR__.'/../init.php');

if (isset($_REQUEST['since']))
{
	$filter_date = (isset($_GET['date'])) ? strtotime($_GET['date']) : strtotime(strftime('%F'));
	$filter_tg = (empty($_GET['tg'])) ? [] : $_GET['tg'];
	$filter_include = $_GET['tgfilter'] ?? 'include';

	if (!is_array($filter_tg))
	{
		$filter_tg = [$filter_tg];
	}

	$latest_file = 0;

	$newcalls = [];
	foreach (RadioSystem::getAll() as $system)
	{
		foreach ($system->getCalls($filter_include, $filter_tg, $_REQUEST['since']) as $call)
		{
			$newdata = $call->getPublicData();
			$newdata['system_name'] = $system['system_name'];
			$newcalls[] = $newdata;

			if (strtotime($call['call_date']) > $latest_file)
			{
				$latest_file = strtotime($call['call_date']);
			}
		}
	}

	usort($newcalls, fn($a,$b) => strtotime($a['call_date']) <=> strtotime($b['call_date']));

	$newcalls = array_slice($newcalls, -300);
	Header('Content-Type: application/json');
	echo json_encode([
		'latest'   => $latest_file,
		'newfiles' => $newcalls,
	]);
	exit();
}

$params = [
	'current_date'       => strftime('%F'),
	'all_systems'        => RadioSystem::getAll(),
	'MAX_CALLS_PER_PAGE' => MAX_CALLS_PER_PAGE,
];

displayPage('index.html', $params);
