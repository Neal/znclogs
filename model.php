<?php

require_once __dir__ . '/config.php';

function get_log_list() {
	$ret = array();
	$scanned_directory = array_diff(scandir(LOG_FILE_LOCATION, SCANDIR_SORT_DESCENDING), array('..', '.'));

	foreach ($scanned_directory as $log_file) {
		$t = split('_', str_replace('.log', null, $log_file));
		$network = strtolower($t[0]);
		$channel = $t[1];
		$date = $t[2];
		$ret[$network][$channel][$date] = array(
			'location' => LOG_FILE_LOCATION.$log_file,
			'size' => filesize(LOG_FILE_LOCATION.$log_file)
		);
	}

	foreach ($ret as $network => $channels) {
		foreach ($channels as $channel => $channel_data)
			if (empty($channel))
				unset($ret[$network][$channel]);

		uksort($ret[$network], 'strcasecmp');
	}

	uksort($ret, 'strcasecmp');

	return $ret;
}

function get_array_key($array, $key, $default = NULL) {
	return array_key_exists($key, $array) ? $array[$key] : $default;
}

$illegal_characters = array('..', '/', '~', '#', "\\");

$get_network = str_replace($illegal_characters, null, get_array_key($_GET, 'network'));
$get_channel = str_replace($illegal_characters, null, get_array_key($_GET, 'channel'));
$get_date = str_replace($illegal_characters, null, get_array_key($_GET, 'date'));

$networks = get_log_list();

function get_human_readable_date($date) {
	$year = substr($date, 0, 4);
	$month = substr($date, 4, 2);
	$day = substr($date, -2);

	$ret = date('D, F d Y', mktime(0, 0, 0, substr($date, 4, 2), substr($date, -2), substr($date, 0, 4)));

	return $ret;
}

?>
