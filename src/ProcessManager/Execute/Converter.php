<?php

namespace ProcessManager\Execute;

use ProcessManager\Collection;
use ProcessManager\Converter\IConverter;
use ProcessManager\InvalidArgumentException;
use ProcessManager\InvalidExecuteException;
use ProcessManager\InvalidValueException;
use ProcessManager\Mapper;
use ProcessManager\MissingKeyException;
use ProcessManager\Type\IType;

/**
 * Converter to execute.
 *
 * @copyright Copyright (c) 2013 Ledvinka Vít
 * @author Ledvinka Vít, frosty22 <ledvinka.vit@gmail.com>
 *
 * @method onBeforeCheck(IConverter $converter, Mapper $mapper, Collection $collection)
 * @method onBeforeExecute(IConverter $converter, Mapper $mapper, Collection $collection)
 * @method onAfterExecute(IConverter $converter, Mapper $mapper, Collection $collection)
 *
 */
class Converter extends BaseExecute implements IExecute {


	/**
	 * Available events
	 * @var array
	 */
	public $onBeforeCheck = array();
	public $onBeforeExecute = array();
	public $onAfterExecute = array();


	/**
	 * @var IConverter
	 */
	private $converter;


	/**
	 * @var mixed
	 */
	private $target = NULL;


	/**
	 * @var bool
	 */
	private $required;


	/**
	 * @param IConverter $converter
	 * @param null|string $namespace Namespace in collection
	 * @param bool $required Is required call
	 * @throws InvalidArgumentException
	 */
	public function __construct(IConverter $converter, $namespace = NULL, $required = FALSE)
	{
		$this->setNamespace($namespace);;
		$this->converter = $converter;
		$this->required = $required;

		$mapper = $converter->getRequiredMapper();
		if (!$mapper instanceof Mapper)
			throw new InvalidArgumentException('Method "getRequiredMapper" of converter "' . get_class($converter) . '"
							must return instance of ProcessManager\Mapper but "'.gettype($mapper).'" was returned.');

		$type = $converter->getReturnedType();
		if (!$type instanceof IType)
			throw new InvalidArgumentException('Method "getReturnedType" of converter "' . get_class($converter) . '"
							must return instance of ProcessManager\Type\IType but "'.gettype($type).'" was returned.');
	}


	/**
	 * Execute converters.
	 * @param Collection $collection
	 * @return Collection
	 * @throws MissingKeyException
	 * @throws InvalidValueException
	 * @throws InvalidArgumentException
	 * @throws InvalidExecuteException
	 */
	public function execute(Collection $collection)
	{
		if (count($this->target) === 0) {
			if ($this->isRequired())
				throw new InvalidExecuteException('Converter "' . get_class($this->converter) . '"
						is required to call but no targets exists.');
			return $collection;
		}

		$collection = $this->getTargetCollection($collection);

		$result = $this->run($collection);
		foreach ($this->target as $name)
			$collection->$name = $result;

		return $collection;
	}


	/**
	 * Set target value(s) in collection
	 * @param string $target
	 * @return $this
	 */
	public function setTarget($target)
	{
		if (is_string($target))
			$target = array($target);

		$this->target = is_array($target) ? $target : array();
		return $this;
	}


	/**
	 * Get target value(s) in collection
	 * @return array|NULL
	 */
	public function getTarget()
	{
		return $this->target;
	}


	/**
	 * @return \ProcessManager\Converter\IConverter
	 */
	public function getConverter()
	{
		return $this->converter;
	}


	/**
	 * Set if is required call
	 * @param boolean $required
	 */
	public function setRequired($required)
	{
		$this->required = $required;
	}


	/**
	 * Get if is required call
	 * @return boolean
	 */
	public function isRequired()
	{
		return $this->required;
	}


	/**
	 * Run converter on collection
	 * @param Collection $collection
	 * @return mixed
	 * @throws MissingKeyException
	 * @throws InvalidValueException
	 * @throws InvalidArgumentException
	 */
	protected function run(Collection $collection)
	{
		$mapper = $this->converter->getRequiredMapper();

		$this->onBeforeCheck($this->converter, $mapper, $collection);

		try {
			$mapper->check($collection);
		} catch (InvalidValueException $e) {
			if ($this->isRequired())
				throw $e;
			return NULL;
		} catch (MissingKeyException $e) {
			if ($this->isRequired())
				throw $e;
			return NULL;
		}

		$this->onBeforeExecute($this->converter, $mapper, $collection);
		$result = $this->converter->execute($collection);
		$this->onAfterExecute($this->converter, $mapper, $collection);

		if ($result !== NULL) {
			$type = $this->converter->getReturnedType();

			if (!$type->check($result))
				throw new InvalidArgumentException('Method "execute" of converter "' . get_class($this->converter) . '"
					must return valid value of type "'.gettype($type).'" but "'.gettype($result).'" was returned.');

		}

		return $result;
	}



}