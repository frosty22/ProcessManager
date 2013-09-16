<?php

namespace ProcessManager;

/**
 * Represent of data collection.
 *
 * @copyright Copyright (c) 2013 Ledvinka Vít
 * @author Ledvinka Vít, frosty22 <ledvinka.vit@gmail.com>
 *
 */
class Collection implements \ArrayAccess {


	/**
	 * @var array
	 */
	private $values = array();


	/**
	 * @param array $array
	 */
	public function __construct(array $array = array())
	{
		foreach ($array as $key => $value)
			$this->$key = $value;
	}


	/**
	 * Check if key exist
	 * @param string|int $name
	 * @return bool
	 */
	public function exist($name)
	{
		$collection = $this->getTargetCollection($name);

		if ($collection === NULL)
			return FALSE;

		if ($collection === $this)
			return isset($this->values[$this->getTargetKey($name)]);

		return isset($collection[$this->getTargetKey($name)]);
	}


	/**
	 * Get value
	 * @param string|int $name
	 * @return mixed|NULL
	 */
	public function __get($name)
	{
		$key = $this->getTargetKey($name);
		$collection = $this->getTargetCollection($name);

		if ($collection === NULL)
			return NULL;

		if ($collection === $this)
			return isset($this->values[$key]) ? $this->values[$key] : NULL;

		return $collection->$key;
	}


	/**
	 * Set value
	 * @param string|int $name
	 * @param mixed $value
	 * @throws InvalidArgumentException
	 */
	public function __set($name, $value)
	{
		if (!is_string($name) && !is_integer($name))
			throw new InvalidArgumentException("Name of type must be string or number.");

		if (strpos($name, ".")) {
			$key = mb_substr($name, 0, mb_strpos($name, "."));
			$name = mb_substr($name, mb_strlen($key) + 1);

			if (!isset($this->values[$key]))
				$this->values[$key] = new Collection();

			$this->values[$key]->$name = $value;
		} else {
			$this->values[$name] = $value;
		}
	}


	/**
	 * Check if exist key
	 * @param string|int $name
	 * @return bool
	 */
	public function __isset($name)
	{
		return $this->exist($name);
	}


	/**
	 * @param string|int $name
	 */
	public function __unset($name)
	{
		$collection = $this->getTargetCollection($name);

		if ($collection === $this)
			unset($this->values[$this->getTargetKey($name)]);
		elseif ($collection instanceof Collection)
			unset($collection[$this->getTargetKey($name)]);
	}


	/**
	 * @param string $offset
	 * @return bool
	 */
	public function offsetExists($offset)
	{
		return $this->exist($offset);
	}


	/**
	 * @param string|int $offset
	 * @param mixed $value
	 */
	public function offsetSet($offset, $value)
	{
		$this->__set($offset, $value);
	}


	/**
	 * @param string|int $offset
	 * @return mixed
	 */
	public function offsetGet($offset)
	{
		return $this->__get($offset);
	}


	/**
	 * @param string|int $offset
	 */
	public function offsetUnset($offset)
	{
		$this->__unset($offset);
	}


	/**
	 * Get target collection
	 * @param string $fullName
	 * @return Collection|NULL
	 */
	private function getTargetCollection($fullName)
	{
		$parts = Explode('.', $fullName);
		unset($parts[count($parts) - 1]);

		if (!count($parts))
			return $this;

		$collection = $this;
		foreach ($parts as $part) {
			if (!$collection->$part instanceof Collection)
				return NULL;
			$collection = $collection->$part;
		}

		return $collection;
	}


	/**
	 * Get target key
	 * @param string $fullName
	 * @return string
	 */
	private function getTargetKey($fullName)
	{
		$dot = strrpos($fullName, '.');
		if ($dot === FALSE) return $fullName;
		return substr($fullName, $dot + 1);
	}

}