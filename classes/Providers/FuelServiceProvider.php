<?php

/*
 * This file is part of the Fuel Stash package.
 *
 * (c) Indigo Development Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Indigo\Fuel\Stash\Providers;

use Fuel\Dependency\ServiceProvider;

/**
 * Provides stash service
 *
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 */
class FuelServiceProvider extends ServiceProvider
{
	/**
	 * {@inheritdoc}
	 */
	public $provides = [
		'stash',
		'stash.file',
		'stash.sqlite',
		'stash.apc',
		'stash.memcached',
		'stash.redis',
		'stash.ephemeral',
		'stash.composite',
	];

	/**
	 * {@inheritdoc}
	 */
	public function provide()
	{
		$this->register('stash', function($dic, $config = [])
		{
			// load the default config
			$defaults = \Config::get('cache', []);

			// $config can be either an array of config settings or the name of the storage driver
			if ( ! empty($config) and ! is_array($config) and ! is_null($config))
			{
				$config = ['driver' => $config];
			}

			// Overwrite default values with given config
			$config = array_merge($defaults, (array) $config);

			$name = $dic->getName();

			if (!empty($config['driver']))
			{
				$driver = $config['driver'];
			}
			elseif ($dic->isMultiton() and in_array($name, ['file', 'memcached', 'redis'])) {
				$driver = $name;
			}
			else
			{
				$driver = 'ephemeral';
			}

			$config = \Arr::get($config, $driver, []);

			$driver = $dic->resolve('stash.driver.'.$driver, [$config]);

			return $doc->resolve('Stash\\Pool', [$driver]);
		});

		$this->register('stash.file', function($dic, $config = [])
		{
			$driver = $dic->resolve('Stash\\Driver\\FileSystem');
			$driver->setOptions($config);

			return $driver;
		});

		$this->register('stash.sqlite', function($dic, $config = [])
		{
			$driver = $dic->resolve('Stash\\Driver\\Sqlite');
			$driver->setOptions($config);

			return $driver;
		});

		$this->register('stash.apc', function($dic, $config = [])
		{
			$driver = $dic->resolve('Stash\\Driver\\Apc');
			$driver->setOptions($config);

			return $driver;
		});

		$this->register('stash.memcached', function($dic, $config = [])
		{
			$driver = $dic->resolve('Stash\\Driver\\Memcached');
			$driver->setOptions($config);

			return $driver;
		});

		$this->register('stash.redis', function($dic, $config = [])
		{
			$driver = $dic->resolve('Stash\\Driver\\Redis');
			$driver->setOptions($config);

			return $driver;
		});

		$this->register('stash.ephemeral', function($dic, $config = [])
		{
			$driver = $dic->resolve('Stash\\Driver\\Ephemeral');
			$driver->setOptions($config);

			return $driver;
		});

		$this->register('stash.composite', function($dic, $config = [])
		{
			$driver = $dic->resolve('Stash\\Driver\\Composite');
			$driver->setOptions($config);

			return $driver;
		});
	}
}
