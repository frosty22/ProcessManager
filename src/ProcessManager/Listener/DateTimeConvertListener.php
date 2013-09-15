<?php

namespace ProcessManager\Listener;

use Kdyby\Events\Subscriber;
use ProcessManager\Collection;
use ProcessManager\InvalidArgumentException;
use ProcessManager\Process\IProcess;
use ProcessManager\Type\Object;

/**
 * Convert string or timestamp to DateTime if is necessary
 *
 * @copyright Copyright (c) 2013 Ledvinka Vít
 * @author Ledvinka Vít, frosty22 <ledvinka.vit@gmail.com>
 *
 */
class DateTimeConvertListener extends \Nette\Object implements Subscriber {


	/**
	 * @param IProcess $process
	 * @param Collection $collection
	 * @throws InvalidArgumentException
	 */
	public function onBeforeProcessCheck(IProcess $process, Collection $collection)
	{
		foreach ($process->getRequiredMapper() as $key => $type) {
			if (($type instanceof Object) && ($type->getClassName() == 'DateTime')) {
				if (empty($collection->$key))
						$collection->$key = NULL;
				elseif (is_string($collection->$key) || is_integer($collection->$key))
					$collection->$key = \Nette\DateTime::from($collection->$key);
				elseif (!$collection->$key instanceof \DateTime)
					throw new InvalidArgumentException("Invalid datetime on key '$key',
									but '" . gettype($collection->$key) . "' given.");
			}
		}
	}


	/**
	 * @return array
	 */
	public function getSubscribedEvents()
	{
		return array('ProcessManager\ProcessManager::onBeforeProcessCheck');
	}

}