<?php

namespace ProcessManager\Type;

/**
 * Interface for all types.
 *
 * @copyright Copyright (c) 2013 Ledvinka Vít
 * @author Ledvinka Vít, frosty22 <ledvinka.vit@gmail.com>
 *
 */
interface IType {


	/**
	 * Set type as required
	 * @param bool $required
	 * @return $this
	 */
	public function setRequired($required = TRUE);


	/**
	 * Is type required
	 * @return bool
	 */
	public function isRequired();


	/**
	 * Check value if is correct
	 * @param string $value
	 * @return bool
	 */
	public function check($value);


	/**
	 * Sanitize value if is needed
	 * @param mixed $value
	 * @return mixed
	 */
	public function sanitize($value);

}