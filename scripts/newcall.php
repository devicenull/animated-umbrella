<?php
// note: if this exits non-zero, lsyncd dies
require(__DIR__.'/../init.php');

// 18:07:28 Exec: /bin/sh [-c] [php /home/trunkrecorder/ui/scripts/newcall.php "$1"] [/bin/sh] [/home/trunkrecorder/audio_files//NJICS/2021/12/28/3329-1640732846_773068750.wav]

$filename = $argv[1];

$pathinfo = pathinfo($filename);
if ($pathinfo['extension'] != RadioCall::FILE_TYPE)
{
	echo "Invalid extension: {$pathinfo['extension']}\n";
	exit(0);
}

if (filesize($filename) < 1024)
{
	// Filtered because they will produce an error when attempting playback.
	exit(0);
}

$basename = basename($filename, '.'.RadioCall::FILE_TYPE);
[$TGID, $TIME, $FREQ] = preg_split('/[-_]/', $basename);

$params = [
	'absolute_path' => $filename,
	'size_kb'       => round(filesize($filename) / 1024),
	'TGID'          => $TGID,
	'call_date'     => strftime('%F %T', $TIME),
	'frequency'     => $FREQ,
	'SYSTEMID'      => SYSTEMID_DEFAULT,
];

$call = new RadioCall();
if (!$call->add($params))
{
	echo "Unable to add call: ".$call->error."\n";
	//exit(1);
}

echo "Call added\n";

// FIXME: should probably just hit radioreference daily, definitely shouldn't be in
// this script either!
$system = new RadioSystem(['SYSTEMID' => SYSTEMID_DEFAULT]);
if (strtotime($system['last_talkgroup_update']) < filemtime($system['talkgroup_path']))
{
	$system->refreshTalkGroups();
}
