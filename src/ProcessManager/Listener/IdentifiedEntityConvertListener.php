<?php

namespace ProcessManager\Listener;

use Ale\Entities\IdentifiedEntity;
use Kdyby\Doctrine\EntityManager;
use Nette\Utils\Validators;
use ProcessManager\Collection;
use ProcessManager\InvalidIdentifiedException;
use ProcessManager\Mapper;
use ProcessManager\Process\IProcess;
use ProcessManager\Type;

/**
 * Before process check in manager is entity type convert to entity type, if is needed.
 *
 * @copyright Copyright (c) 2013 Ledvinka Vít
 * @author Ledvinka Vít, frosty22 <ledvinka.vit@gmail.com>
 *
 */
class IdentifiedEntityConvertListener extends \Nette\Object implements \Kdyby\Events\Subscriber
{


	/**
	 * @var EntityManager
	 */
	private $em;


	/**
	 * @param EntityManager $em
	 */
	public function __construct(EntityManager $em)
	{
		$this->em = $em;
	}


	/**
	 * Return array of subscribed events.
	 * @return array
	 */
	public function getSubscribedEvents()
	{
		return array('ProcessManager\ProcessManager::onBeforeProcessCheck');
	}


	/**
	 * Called before collection is checked.
	 * @param IProcess $process
	 * @param Mapper $mapper
	 * @param Collection $collection
	 * @throws InvalidIdentifiedException
	 */
	public function onBeforeCheck(IProcess $process, Mapper $mapper, Collection $collection)
	{
		foreach ($mapper as $name => $type) {
			if ($type instanceof Type\Object) {
				if (is_subclass_of($type->getClassName(), 'Ale\Entities\IdentifiedEntity')) {
					$value = $collection->$name;

					if (Validators::isNumericInt($value)) {
						$entity = $this->findById($type->getClassName(), $value);

						if (!$entity)
							throw new InvalidIdentifiedException($name, $value,
								"Identified {$value} not found in {$name}.");

						$collection->$name = $entity;
					}

				}
			}

		}

	}


	/**
	 * @param string $repositoryName
	 * @param int $id
	 * @return IdentifiedEntity
	 */
	private function findById($repositoryName, $id)
	{
		return $this->em->find($repositoryName, $id);
	}

}