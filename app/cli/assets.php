<?php

$dir = __DIR__ . '/../../public/assets';

$files = [
	'scripts' => [
		'/js/bundle.js' => '/js/bundle.{time}.min.js'
	],
	'styles' => [
		'/css/bundle.css' => '/css/bundle.{time}.min.css'
	]
];

$clear = [
	'/js/bundle.*.min.js',
	'/css/bundle.*.min.css'
];

foreach ($clear as $pattern) {
	foreach (glob($dir . $pattern) as $file) {
		unlink($file);
	}
}

$config = [];

foreach ($files as $ns => $items) {
	$config[$ns] = [];
	foreach ($items as $from => $to) {
		$file = $dir . $from;
		$time = filemtime($file);
		$time = base_convert($time, 10, 36);
		$to = str_replace('{time}', $time, $to);

		copy($file, $dir . $to);

		$config[$ns][] = '/assets' . $to;
		printf("%s => %s\n", $from, $to);
	}
}

file_put_contents($dir . '/config.json', json_encode($config, JSON_PRETTY_PRINT));