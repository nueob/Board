<?php

/**
 * This file is part of the CodeIgniter 4 framework.
 *
 * (c) CodeIgniter Foundation <admin@codeigniter.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CodeIgniter\Config;

use CodeIgniter\Autoloader\Autoloader;
use CodeIgniter\Autoloader\FileLocator;
use CodeIgniter\CodeIgniter;
use Config\Autoload;
use Config\Modules;
use CodeIgniter\Cache\CacheInterface;
use CodeIgniter\CLI\Commands;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Database\MigrationRunner;
use CodeIgniter\Debug\Exceptions;
use CodeIgniter\Debug\Iterator;
use CodeIgniter\Debug\Timer;
use CodeIgniter\Debug\Toolbar;
use CodeIgniter\Email\Email;
use CodeIgniter\Encryption\EncrypterInterface;
use CodeIgniter\Filters\Filters;
use CodeIgniter\Format\Format;
use CodeIgniter\Honeypot\Honeypot;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\CURLRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\Negotiate;
use CodeIgniter\HTTP\RedirectResponse;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\Response;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\HTTP\URI;
use CodeIgniter\Images\Handlers\BaseHandler;
use CodeIgniter\Language\Language;
use CodeIgniter\Log\Logger;
use CodeIgniter\Pager\Pager;
use CodeIgniter\Router\RouteCollection;
use CodeIgniter\Router\RouteCollectionInterface;
use CodeIgniter\Router\Router;
use CodeIgniter\Security\Security;
use CodeIgniter\Session\Session;
use CodeIgniter\Throttle\Throttler;
use CodeIgniter\Typography\Typography;
use CodeIgniter\Validation\Validation;
use CodeIgniter\View\Cell;
use CodeIgniter\View\Parser;
use CodeIgniter\View\RendererInterface;
use CodeIgniter\View\View;

/**
 * Services Configuration file.
 *
 * Services are simply other classes/libraries that the system uses
 * to do its job. This is used by CodeIgniter to allow the core of the
 * framework to be swapped out easily without affecting the usage within
 * the rest of your application.
 *
 * This is used in place of a Dependency Injection container primarily
 * due to its simplicity, which allows a better long-term maintenance
 * of the applications built on top of CodeIgniter. A bonus side-effect
 * is that IDEs are able to determine what class you are calling
 * whereas with DI Containers there usually isn't a way for them to do this.
 *
 * @see http://blog.ircmaxell.com/2015/11/simple-easy-risk-and-change.html
 * @see http://www.infoq.com/presentations/Simple-Made-Easy
 *
 * @method static CacheInterface cache(\Config\Cache $config = null, $getShared = true)
 * @method static CLIRequest clirequest(\Config\App $config = null, $getShared = true)
 * @method static CodeIgniter codeigniter(\Config\App $config = null, $getShared = true)
 * @method static Commands commands($getShared = true)
 * @method static CURLRequest curlrequest($options = [], \CodeIgniter\HTTP\ResponseInterface $response = null, \Config\App $config = null, $getShared = true)
 * @method static Email email($config = null, $getShared = true)
 * @method static EncrypterInterface encrypter(\Config\Encryption $config = null, $getShared = false)
 * @method static Exceptions exceptions(\Config\Exceptions $config = null, \CodeIgniter\HTTP\IncomingRequest $request = null, \CodeIgniter\HTTP\Response $response = null, $getShared = true)
 * @method static Filters filters(\Config\Filters $config = null, $getShared = true)
 * @method static Format format(\Config\Format $config = null, $getShared = true)
 * @method static Honeypot honeypot(\Config\Honeypot $config = null, $getShared = true)
 * @method static BaseHandler image($handler = null, \Config\Images $config = null, $getShared = true)
 * @method static Iterator iterator($getShared = true)
 * @method static Language language($locale = null, $getShared = true)
 * @method static Logger logger($getShared = true)
 * @method static MigrationRunner migrations(\Config\Migrations $config = null, \CodeIgniter\Database\ConnectionInterface $db = null, $getShared = true)
 * @method static Negotiate negotiator(\CodeIgniter\HTTP\RequestInterface $request = null, $getShared = true)
 * @method static Pager pager(\Config\Pager $config = null, \CodeIgniter\View\RendererInterface $view = null, $getShared = true)
 * @method static Parser parser($viewPath = null, \Config\View $config = null, $getShared = true)
 * @method static View renderer($viewPath = null, \Config\View $config = null, $getShared = true)
 * @method static IncomingRequest request(\Config\App $config = null, $getShared = true)
 * @method static Response response(\Config\App $config = null, $getShared = true)
 * @method static RedirectResponse redirectresponse(\Config\App $config = null, $getShared = true)
 * @method static RouteCollection routes($getShared = true)
 * @method static Router router(\CodeIgniter\Router\RouteCollectionInterface $routes = null, \CodeIgniter\HTTP\Request $request = null, $getShared = true)
 * @method static Security security(\Config\App $config = null, $getShared = true)
 * @method static Session session(\Config\App $config = null, $getShared = true)
 * @method static Throttler throttler($getShared = true)
 * @method static Timer timer($getShared = true)
 * @method static Toolbar toolbar(\Config\Toolbar $config = null, $getShared = true)
 * @method static URI uri($uri = null, $getShared = true)
 * @method static Validation validation(\Config\Validation $config = null, $getShared = true)
 * @method static Cell viewcell($getShared = true)
 * @method static Typography typography($getShared = true)
 */
class BaseService
{
	/**
	 * Cache for instance of any services that
	 * have been requested as a "shared" instance.
	 * Keys should be lowercase service names.
	 *
	 * @var array
	 */
	protected static $instances = [];

	/**
	 * Mock objects for testing which are returned if exist.
	 *
	 * @var array
	 */
	protected static $mocks = [];

	/**
	 * Have we already discovered other Services?
	 *
	 * @var boolean
	 */
	protected static $discovered = false;

	/**
	 * A cache of other service classes we've found.
	 *
	 * @var array
	 */
	protected static $services = [];

	/**
	 * A cache of the names of services classes found.
	 *
	 * @var array<string>
	 */
	private static $serviceNames = [];

	/**
	 * Returns a shared instance of any of the class' services.
	 *
	 * $key must be a name matching a service.
	 *
	 * @param string $key
	 * @param mixed  ...$params
	 *
	 * @return mixed
	 */
	protected static function getSharedInstance(string $key, ...$params)
	{
		$key = strtolower($key);

		// Returns mock if exists
		if (isset(static::$mocks[$key]))
		{
			return static::$mocks[$key];
		}

		if (! isset(static::$instances[$key]))
		{
			// Make sure $getShared is false
			array_push($params, false);

			static::$instances[$key] = static::$key(...$params);
		}

		return static::$instances[$key];
	}

	/**
	 * The Autoloader class is the central class that handles our
	 * spl_autoload_register method, and helper methods.
	 *
	 * @param boolean $getShared
	 *
	 * @return Autoloader
	 */
	public static function autoloader(bool $getShared = true)
	{
		if ($getShared)
		{
			if (empty(static::$instances['autoloader']))
			{
				static::$instances['autoloader'] = new Autoloader();
			}

			return static::$instances['autoloader'];
		}

		return new Autoloader();
	}

	/**
	 * The file locator provides utility methods for looking for non-classes
	 * within namespaced folders, as well as convenience methods for
	 * loading 'helpers', and 'libraries'.
	 *
	 * @param boolean $getShared
	 *
	 * @return FileLocator
	 */
	public static function locator(bool $getShared = true)
	{
		if ($getShared)
		{
			if (empty(static::$instances['locator']))
			{
				static::$instances['locator'] = new FileLocator(static::autoloader());
			}

			return static::$mocks['locator'] ?? static::$instances['locator'];
		}

		return new FileLocator(static::autoloader());
	}

	/**
	 * Provides the ability to perform case-insensitive calling of service
	 * names.
	 *
	 * @param string $name
	 * @param array  $arguments
	 *
	 * @return mixed
	 */
	public static function __callStatic(string $name, array $arguments)
	{
		$service = static::serviceExists($name);

		if ($service === null)
		{
			return null;
		}

		return $service::$name(...$arguments);
	}

	/**
	 * Check if the requested service is defined and return the declaring
	 * class. Return null if not found.
	 *
	 * @param string $name
	 *
	 * @return string|null
	 */
	public static function serviceExists(string $name): ?string
	{
		static::buildServicesCache();
		$services = array_merge(self::$serviceNames, [Services::class]);
		$name     = strtolower($name);

		foreach ($services as $service)
		{
			if (method_exists($service, $name))
			{
				return $service;
			}
		}

		return null;
	}

	/**
	 * Reset shared instances and mocks for testing.
	 *
	 * @param boolean $initAutoloader Initializes autoloader instance
	 */
	public static function reset(bool $initAutoloader = false)
	{
		static::$mocks     = [];
		static::$instances = [];

		if ($initAutoloader)
		{
			static::autoloader()->initialize(new Autoload(), new Modules());
		}
	}

	/**
	 * Inject mock object for testing.
	 *
	 * @param string $name
	 * @param mixed  $mock
	 */
	public static function injectMock(string $name, $mock)
	{
		static::$mocks[strtolower($name)] = $mock;
	}

	/**
	 * Will scan all psr4 namespaces registered with system to look
	 * for new Config\Services files. Caches a copy of each one, then
	 * looks for the service method in each, returning an instance of
	 * the service, if available.
	 *
	 * @param string $name
	 * @param array  $arguments
	 *
	 * @return mixed
	 *
	 * @deprecated
	 *
	 * @codeCoverageIgnore
	 */
	protected static function discoverServices(string $name, array $arguments)
	{
		if (! static::$discovered)
		{
			$config = config('Modules');

			if ($config->shouldDiscover('services'))
			{
				$locator = static::locator();
				$files   = $locator->search('Config/Services');

				if (empty($files))
				{
					// no files at all found - this would be really, really bad
					return null;
				}

				// Get instances of all service classes and cache them locally.
				foreach ($files as $file)
				{
					$classname = $locator->getClassname($file);

					if (! in_array($classname, ['CodeIgniter\\Config\\Services'], true))
					{
						static::$services[] = new $classname();
					}
				}
			}

			static::$discovered = true;
		}

		if (! static::$services)
		{
			// we found stuff, but no services - this would be really bad
			return null;
		}

		// Try to find the desired service method
		foreach (static::$services as $class)
		{
			if (method_exists($class, $name))
			{
				return $class::$name(...$arguments);
			}
		}

		return null;
	}

	protected static function buildServicesCache(): void
	{
		if (! static::$discovered)
		{
			$config = config('Modules');

			if ($config->shouldDiscover('services'))
			{
				$locator = static::locator();
				$files   = $locator->search('Config/Services');

				// Get instances of all service classes and cache them locally.
				foreach ($files as $file)
				{
					$classname = $locator->getClassname($file);

					if ($classname !== 'CodeIgniter\\Config\\Services')
					{
						self::$serviceNames[] = $classname;
						static::$services[]   = new $classname();
					}
				}
			}

			static::$discovered = true;
		}
	}
}