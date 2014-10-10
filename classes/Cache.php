<?php

/*
 * This file is part of the Fuel Stash package.
 *
 * (c) Indigo Development Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Indigo\Fuel\Stash;

use Indigo\Fuel\Dependency\Container as DiC;

/**
 * Overrides the Fuel Cache class
 *
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 */
class Cache
{
	/**
	 * {@inheritdoc}
	 */
	public static function forge($identifier, $config = [])
	{
		return DiC::multiton('stash.pool', $identifier, [$config]);
	}

	/**
	 * Returns an item from the default pool
	 *
	 * @param string $identifier
	 *
	 * @return Stash\Item
	 */
	protected static function getItem($identifier)
	{
		$identifier = str_replace('.', '/', $identifier);

		$pool = DiC::multiton('stash.pool');

		return $pool->getItem($identifier);
	}

	/**
	 * {@inheritdoc}
	 */
	public static function get($identifier, $use_expiration = true)
	{
		$item = static::getItem($identifier);

		if ($item->isMiss())
		{
			throw new \CacheNotFoundException('not found');
		}

		return $item->get();
	}

	/**
	 * {@inheritdoc}
	 */
	public static function set($identifier, $contents = null, $expiration = false, $dependencies = array())
	{
		$contents = \Fuel::value($contents);

		$item = static::getItem($identifier);

		$item->set($contents, $expiration);
	}

	/**
	 * {@inheritdoc}
	 */
	public static function call($identifier, $callback, $args = array(), $expiration = null, $dependencies = array())
	{
		$item = static::getItem($identifier);

		$contents = $item->get();

		if ($item->isMiss())
		{
			$contents = call_fuel_func_array($callback, $args);

			$contents = \Fuel::value($contents);

			$item->set($contents, $expiration);
		}

		return $contents;
	}

	/**
	 * {@inheritdoc}
	 */
	public static function delete($identifier)
	{
		$item = static::getItem($identifier);

		return $item->clear();
	}

	/**
	 * {@inheritdoc}
	 */
	public static function delete_all($section = null, $driver = null)
	{
		is_null($driver) and $driver = '__default__';

		$pool = DiC::multiton('stash.pool', $driver);

		if (is_null($section)) {
			return $pool->flush();
		}

		$section = str_replace('.', '/', $section);
		$item = $pool->getItem($section);

		return $item->clear();
	}
}
