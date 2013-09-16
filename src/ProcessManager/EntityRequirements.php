<?php

namespace ProcessManager;

use Ale\Entities\BaseEntity;
use Ale\InvalidArgumentException;

/**
 * Read entity requirements
 *
 * @copyright Copyright (c) 2013 Ledvinka Vít
 * @author Ledvinka Vít, frosty22 <ledvinka.vit@gmail.com>
 *
 */
class EntityRequirements extends \Nette\Object {


	/**
	 * @var \EntityMetaReader\EntityReader
	 */
	private $entityMetaReader;


	/**
	 * @param \EntityMetaReader\EntityReader $entityReader
	 */
	public function __construct(\EntityMetaReader\EntityReader $entityReader)
	{
		$this->entityMetaReader = $entityReader;
	}


	/**
	 * @param BaseEntity|string $entity
	 * @return Mapper
	 * @throws UnsupportedException
	 * @throws InvalidArgumentException
	 */
	public function createMapper($entity)
	{
		if ((!$entity instanceof BaseEntity) && !is_string($entity))
			throw new InvalidArgumentException('Parameter must be inherited of BaseEntity or string
					name of class of entity but ' . gettype($entity) . ' given.');

		$mapper = new Mapper();

		$columns = $this->entityMetaReader->getEntityColumns($entity);
		foreach ($columns as $column) {
			if ($column->isValueType()) {
				$type = $column->getAnnotation('Doctrine\ORM\Mapping\Column')->type;

				$add = NULL;
				switch ($type) {
					case "string":
					case "text":
						$add = $mapper->addText($column->getName());
						break;

					case "integer":
					case "smallint":
					case "bigint":
						$add = $mapper->addInteger($column->getName());
						break;

					case "boolean":
						$add = $mapper->addBoolean($column->getName());
						break;

					case "decimal":
					case "float":
						$add = $mapper->addFloat($column->getName());
						break;

					case "date":
					case "time":
					case "datetime":
						$add = $mapper->addObject($column->getName(), 'DateTime');
						break;

					default:
						throw new UnsupportedException('Entity requirenments doesnt support ' . $type . ' of object and array.');

				}

			}
			elseif ($column->isCollectionType()) {
				$add = $mapper->addCollection($column->getName(), $column->getTargetEntity());
			}
			elseif ($column->isEntityType()) {
				$add = $mapper->addObject($column->getName(), $column->getTargetEntity());
			}

			if ($add)
				$add->setRequired($column->isNullable() === FALSE && $column->getDefault() === NULL);

		}

		return $mapper;
	}


}