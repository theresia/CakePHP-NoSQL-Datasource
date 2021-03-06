<?php
/**
 * NoSql Datasource class
 *
 * NoSql Interface for others NoSql layers.
 *
 * PHP 5
 * CakePHP 2
 *
 * Copyright (c) 2012, Wan Chen aka Kamisama
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright 	Copyright (c) 2012, Wan Chen aka Kamisama
 * @link 		https://github.com/kamisama
 * @package 	app.Vendor.NoSql
 * @version 	0.1
 * @license 	MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

/**
 * Exception to be thrown when the nosql layer is not found
 * in the app/Vendor/Nosql/ directory
 *
 * @package app.Vendor.NoSql
 */
class DataSourceNotFoundException extends Exception {}


/**
 * NoSql Interface for others NoSql layers
 *
 * @package app.Vendor.NoSql
 */
class NoSql
{
	/**
	 * Array of references to all instanciated layers
	 *
	 * @var array
	 */
	private static $__sources = array();
	
	
	/**
	 * Return a reference to a nosql layer
	 *
	 * @param string $name Name of the nosql layer
	 * @param mixed $args
	 * @throws DataSourceNotFoundException when the datasource doesn't exists
	 */
	public static function __callStatic($name, $args)
	{
		if (isset(self::$__sources[$name]))
		{
			return self::$__sources[$name]->getInstance();
		}
		
		try{
			$className = $name . 'Source';
			self::load($className);
			$source = $className::getInstance();
			self::$__sources[$name] = $source;
			return $source;
		}
		catch(DataSourceNotFoundException $e)
		{
			throw $e;
		}
	}
	
	
	/**
	 * Instanciate a nosql layer
	 *
	 * All nosql layers files must be in Vendor/NoSql/
	 *
	 * @param string $class Name of the nosql layer
	 * @throws DataSourceNotFoundException when the nosql layer class is not found
	 */
	public static function load($class)
	{
		$path = APP . 'Vendor' . DS . 'NoSql' . DS . $class . '.php';
		if (file_exists($path))
		{
			include_once($path);
		}
		else throw new DataSourceNotFoundException('Unable to load ' . str_replace('Source', '', $class) . ' datasource');
	}
}