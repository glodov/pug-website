<?php

namespace PugWebsite;

use Predis\Client as RedisClient;
use Pug\Pug;

class Application
{
	private $config = [];
	private $client;

	/**
	 * Application constructor.
	 * 
	 * @access public
	 * @param mixed $config The set of array or a name of file in CONFIG_DIR.
	 */
	public function __construct($config)
	{
		if (is_array($config)) {
			$this->config = $config;
		} else {
			$file = CONFIG_DIR . '/' . $config . '.json';
			$this->config = json_decode(file_get_contents($file), true);
		}
		if (!@$this->config['cache']) {
			return true;
		}
		$options = @$this->config['cache']['options'];
		if (empty($options)) {
			$options = '';
		}
		$this->client = new RedisClient($options);
		if (@$this->config['cache']['database']) {
			$this->client->select($this->config['cache']['database']);
		}
	}

	/**
	 * Check if has unexpired page cached by url.
	 * 
	 * @access public
	 * @param string $url The URI string.
	 * @return boolean TRUE if has unexpired page, otherwise FALSE.
	 */
	public function has($url)
	{
		if (!$this->client) {
			return false;
		}
		$key = $this->key($url);
		$time = $this->client->get('time.' . $key);
		if (!$time) {
			return false;
		}
		$timeout = (int) $this->config['cache']['timeout'];
		if ($timeout > 0 && $time + $timeout < time()) {
			return false;
		}
		return true;
	}

	public function show($url)
	{
		$key = $this->key($url);
		return $this->client->get('html.' . $key);
	}

	public function render($url)
	{
		$options = [];
		$pug = new Pug($options);

		var_dump($url); exit;

		return $pug->render($file);
	}

	public function cache($url, $html)
	{
		if (!$this->client) {
			return false;
		}
		$key = $this->key($url);
		$this->client->set('time.' . $key, time());
		$this->client->set('html.' . $key, $html);
		if (@$this->config['cache']['includeGet']) {
			$raw = $this->key($url, true);
			$this->lpush('child.' . $raw, $key);
		}
	}

	/**
	 * Returns key for current url.
	 * 
	 * @access protected
	 * @param string $Url The URI string.
	 * @return string The key for current URI.
	 */
	protected function key($url, $rawOnly = false)
	{
		if (!$url) {
			$url = '/';
		}
		$url = trim($url, '/');
		if (@$this->config['cache']['includeGet'] && !$rawOnly && !empty($_GET)) {
			$url .= '?' . http_build_query($_GET);
		}
		return rtrim(base64_encode($url), '=');
	}
}