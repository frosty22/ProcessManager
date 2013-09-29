<?php

namespace ProcessManager\Reader;

use Nette\Utils\Strings;
use Nette\Utils\Validators;
use ProcessManager\Collection;
use ProcessManager\InvalidArgumentException;
use ProcessManager\Reader\IReader;

/**
 * XML reader.
 *
 * @copyright Copyright (c) 2013 Ledvinka Vít
 * @author Ledvinka Vít, frosty22 <ledvinka.vit@gmail.com>
 *
 */
class XmlReader implements IReader, \Iterator {


	/**
	 * @var int
	 */
	private $position = 0;


	/**
	 * @var array
	 */
	private $array = array();


	/**
	 * @var \SimpleXMLElement
	 */
	private $xml;


	/**
	 * @var array
	 */
	private $keys = array();


	/**
	 * Is collection inited
	 * @var bool
	 */
	private $inited = FALSE;


	/**
	 * Name of iterable element
	 * @var string|null
	 */
	private $iterable = NULL;


	/**
	 * Root element for iterate
	 * @var string|null
	 */
	private $root = NULL;


	/**
	 * @param string $source
	 */
	public function __construct($source)
	{
		if (Validators::isUrl($source)) {
			$source = $this->loadRemoteUrl($source);
		}
		elseif (!Strings::match($source, '~<.*?>.*?</.*?>~mi')) {
			$source = $this->loadFile($source);
		}

		$this->xml = simplexml_load_string($source);
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
	 * @param string $source
	 * @return $this
	 */
	public function addKey($key, $source)
	{
		$this->inited = FALSE;
		$this->keys[$key] = $source;
		return $this;
	}


	/**
	 * Set iterable element
	 * @param string $root
	 * @param string $iterate
	 * @return $this
	 */
	public function setIterable($root, $iterate)
	{
		$this->root = $root;
		$this->iterable = $iterate;
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
	 * Init
	 */
	private function init()
	{
		$this->inited = TRUE;

		$this->array = array();
		if ($this->iterable) {
			foreach ($this->xml->{$this->root}->{$this->iterable} as $item) {
				$this->array[] = $this->getCollection($item);
			}
		} else {
			$this->array[] = $this->getCollection();
		}
	}


	/**
	 * @param string|NULL $item
	 * @return Collection
	 */
	private function getCollection($item = NULL)
	{
		$output = array();

		foreach ($this->keys as $value => $key) {
			$parts = Explode(".", $key);

			if (isset($parts[1]) && $parts[0] === $this->root && $parts[1] === $this->iterable) {
				$element = $item;
				unset($parts[0], $parts[1]);
			} else
				$element = $this->xml;

			$i = 0;
			$attribute = NULL;
			foreach ($parts as $part) {

				$i++;
				if (count($parts) === $i && ($match = Strings::match($part, '~^(.*?)\:([^:]+)$~'))) {
					$attribute = $match[2];
					$part = $match[1];
				}

				if (isset($element->$part) && $element->$part instanceof \SimpleXMLElement) {
					$element = $element->$part;
				} else {
					$output[$value] = NULL;
					continue 2;
				}
			}

			if ($attribute) {
				$output[$value] = isset($element[$attribute]) ? (string)$element[$attribute] : NULL;
			} else
				$output[$value] = !empty($element) ? (string)$element : NULL;
		}

		return new Collection($output);
	}


	/*********** ITERATOR **********/


	public function rewind() {
		$this->init();
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
		return isset($this->array[$this->position]);
	}

}


