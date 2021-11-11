<?php
require(__DIR__.'/../init.php');


// no leading period
$FileType = 'm4a';

/**
*   This allows you to remove the leading portion of a directory name, so you can use
*   absolute paths in the config file, and not have to match that structure in your web server
*
*   For example, if your recordings are in `/home/trunkrecorder/audio_files`, you'd set this to
*   "/home/trunkrecorder/", and define a location block in the nginx config like so:
*    location /audio_files/ {
*        root /home/trunkrecorder/;
*    }
*   Which would let you put your document root anywhere, and not require serving up the entire
*   trunk-recorder directory.
*/
$base_directory_name = '/home/trunkrecorder';

$CONFIG = (function (string $configFilePath = './../../configs/config.json') {
	if (!file_exists($configFilePath)) {
		return false;
	}

	return json_decode(file_get_contents($configFilePath));
})();

if (false === $CONFIG) {
	$error = 'Config file does not exist';
}

if (empty($CONFIG->systems)) {
	$error = 'No systems found in config file';
}

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
