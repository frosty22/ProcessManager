<?php

namespace ProcessManager\Reader;

use Nette\Utils\Strings;
use Nette\Utils\Validators;
use ProcessManager\Collection;
use ProcessManager\ConnectionErrorException;
use ProcessManager\FileNotFoundException;
use ProcessManager\InvalidArgumentException;
use ProcessManager\InvalidStateException;
use ProcessManager\Reader\IReader;
use Sunra\PhpSimple\HtmlDomParser;

/**
 * XML reader.
 *
 * @copyright Copyright (c) 2013 Ledvinka Vít
 * @author Ledvinka Vít, frosty22 <ledvinka.vit@gmail.com>
 *
 */
class HtmlReader extends \Nette\FreezableObject implements IReader {


	/**
	 * @var int
	 */
	private $position = 0;


	/**
	 * @var array
	 */
	private $array;


	/**
	 * @var \simple_html_dom
	 */
	private $html;


	/**
	 * @var array
	 */
	private $keys = array();


	/**
	 * Name of iterable element
	 * @var string|null
	 */
	private $iterable = NULL;


	/**
	 * Iterate keys
	 * @var array
	 */
	private $iterateKeys = array();


	/**
	 * @param string $source
	 */
	public function __construct($source)
	{
		// TODO: Sjednotit načítání source přes jiný objekt, spolu s XML Readrem a dalšími
		if (Validators::isUrl($source)) {
			$source = $this->loadRemoteUrl($source);
		}
		elseif (!Strings::match($source, '~<.*?>.*?</.*?>~mi')) {
			$source = $this->loadFile($source);
		}

		$this->html = HtmlDomParser::str_get_html($source);
	}


	/**
	 * @param \ProcessManager\ProcessManager $manager
	 */
	public function init(\ProcessManager\ProcessManager $manager)
	{
		$this->freeze();
		$this->array = array();

		$results = $this->getResults($this->html, $this->keys);
		if ($this->iterable) {
			foreach ($this->html->find($this->iterable) as $item) {
				$this->array[] = new Collection(array_merge($results, $this->getResults($item, $this->iterateKeys)));
			}
		}
	}


	/**
	 * Set keys
	 * @param array $keys
	 * @return $this
	 */
	public function setKeys(array $keys)
	{
		foreach ($keys as $key => $source)
			$this->addKey($key, $source);
		return $this;
	}


	/**
	 * Add key for collection
	 * @param string $key
	 * @param string $path
	 * @return $this
	 */
	public function addKey($key, $path)
	{
		$this->updating();
		$this->keys[$key] = $path;
		return $this;
	}


	/**
	 * Set iterable element
	 * @param string $path
	 * @param array $keys
	 * @return $this
	 */
	public function setIterable($path, array $keys)
	{
		$this->updating();
		$this->iterable = $path;
		$this->iterateKeys = $keys;
		return $this;
	}


	/**
	 * Load XML from remote URL
	 * @param string $url
	 * @return string
	 * @throws ConnectionErrorException
	 */
	private function loadRemoteUrl($url)
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

		$data = curl_exec($ch);
		$http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);

		if ($http_status != 200)
			throw new ConnectionErrorException("Invalid HTTP status of URL '{$url}'.");

		curl_close($ch);

		return $data;
	}


	/**
	 * Load XML source from file
	 * @param string $filename
	 * @return string
	 * @throws FileNotFoundException
	 */
	private function loadFile($filename)
	{
		if (!file_exists($filename))
			throw new FileNotFoundException("File '$filename' not found.'");

		return file_get_contents($filename);
	}


	/**
	 * @param \simple_html_dom_node $node
	 * @param array $keys
	 * @return array
	 */
	private function getResults($node, array $keys)
	{
		if (!$node instanceof \simple_html_dom && !$node instanceof \simple_html_dom_node)
			throw new InvalidArgumentException('Error in parse node.');

		$output = array();

		foreach ($keys as $key => $path) {

			$attribute = NULL;
			if ($match = Strings::match($path, '~^(.*?)\:([^:]+)$~')) {
				$path = $match[1];
				$attribute = $match[2];
			}

			if ($path == "") $element = $node;
			else $element = $node->find($path, 0);

			if (!$element)
				$output[$key] = NULL;
			else {
				if ($attribute) $output[$key] = trim($element->{$attribute});
				else $output[$key] = trim(html_entity_decode(str_replace("&nbsp;", " ", $element->plaintext)));
			}

		}

		return $output;
	}


	/*********** ITERATOR **********/


	public function rewind() {
		$this->position = 0;
	}


	public function current() {
		return $this->array[$this->position];
	}


	public function key() {
		return $this->position;
	}


	public function next() {
		++$this->position;
	}


	public function valid() {
		if (!isset($this->array))
			throw new InvalidStateException("Do not call reader directly! Only for use with ProcessManager.");

		return isset($this->array[$this->position]);
	}

}


