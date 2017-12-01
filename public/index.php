<?php

define('PUBLIC_DIR', __DIR__);
define('CONFIG_DIR', __DIR__ . '/../app/config');

include(__DIR__ . '/../vendor/autoload.php');

define('DEV_MODE', true);

$config = 'production';
if (defined('DEV_MODE') && DEV_MODE) {
	$config = 'development';
}

$app = new PugWebsite\Application($config);

$url = @$_GET['_url'];

$html = null;
if ($app->has($url)) {
	$html = $app->show($url);
} else {
	$html = $app->render($url);
	$app->cache($url, $html);
}

header('Content-Type: text/html;charset: utf8');
echo $html;
