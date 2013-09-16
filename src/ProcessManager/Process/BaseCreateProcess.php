<?php

namespace ProcessManager\Process;

use Ale\Entities\BaseEntity;
use Kdyby\Doctrine\EntityManager;
use ProcessManager\Collection;
use ProcessManager\EntityRequirements;

/**
 * Abstract class for create processes.
 *
 * @copyright Copyright (c) 2013 Ledvinka Vít
 * @author Ledvinka Vít, frosty22 <ledvinka.vit@gmail.com>
 *
 */
abstract class BaseCreateProcess extends \Nette\Object implements IProcess {


	/**
	 * @var EntityRequirements
	 */
	private $entityRequirements;


	/**
	 * @var EntityManager
	 */
	private $entityManager;


	/**
	 * @param EntityRequirements $er
	 * @param EntityManager $entityManager
	 */
	public function __construct(EntityRequirements $er, EntityManager $entityManager)
	{
		$this->entityRequirements = $er;
		$this->entityManager = $entityManager;
	}


	/**
	 * Return required mapper, for data input.
	 * @return \ProcessManager\Mapper
	 */
	public function getRequiredMapper()
	{
		return $this->entityRequirements->createMapper($this->getEntityName());
	}


	/**
	 * @param Collection $collection
	 * @return mixed
	 */
	public function execute(Collection $collection)
	{
		$entity = $this->createEntity($collection);
		$this->entityManager->getDao($this->getEntityName())->save($entity);
		return $entity;
	}


	/**
	 * Create entity
	 * @param Collection $collection
	 * @return BaseEntity
	 */
	protected function createEntity(Collection $collection)
	{
		$mapper = $this->getRequiredMapper();

		$entity = new $this->getEntityName();
		foreach ($mapper as $name => $type) {
			if (isset($collection->$name))
				$entity->$name = $collection->$name;
		}

		return $entity;
	}


	/**
	 * Get entity name
	 * @return string
	 */
	abstract protected function getEntityName();

}