<?php

namespace ProcessManager;

use ProcessManager\Type;

/**
 * Mapper for required data of processes
 *
 * @copyright Copyright (c) 2013 Ledvinka Vít
 * @author Ledvinka Vít, frosty22 <ledvinka.vit@gmail.com>
 *
 */
class Mapper extends \Nette\Object {


	/**
	 * @var Type\IType[]
	 */
	private $types = array();


	/**
	 * Add type
	 * @param string|int $name
	 * @param Type\IType $type
	 * @return Type\IType
	 * @throws InvalidArgumentException
	 */
	public function addType($name, Type\IType $type)
	{
		if (!is_string($name) && !is_integer($name))
			throw new InvalidArgumentException("Name of type must be string or number.");

		if (isset($this->types[$name]))
			throw new InvalidArgumentException("Type with name '$name' already exist in mapper.");

		$this->types[$name] = $type;
		return $type;
	}


	/**
	 * Check collection and set validated
	 * @param Collection $collection
	 * @return Collection
	 * @throws InvalidValueException
	 * @throws MissingKeyException
	 */
	public function check(Collection $collection)
	{
		foreach ($this->types as $name => $type) {
			if ($type->isRequired() && !isset($collection->$name)) {
				throw new MissingKeyException($name);
			}

			if (isset($collection->$name)) {

				if (!$type->check($collection->$name)) {
					throw new InvalidValueException($name, $collection->$name);
				}

				$collection->$name = $type->sanitize($collection->$name);
			}
		}

		$collection->setChecked();

		return $collection;
	}


	/**
	 * Add text type
	 * @param string $name
	 * @return Type\Text
	 */
	public function addText($name)
	{
		return $this->addType($name, new Type\Text());
	}


	/**
	 * Add integer type
	 * @param string $name
	 * @return Type\Integer
	 */
	public function addInteger($name)
	{
		return $this->addType($name, new Type\Integer());
	}


}