<?php

declare(strict_types=1);

namespace muqsit\querymanipulator\query\component;

final class QueryManipulatorComponentFactory{

	/** @var array<string, class-string<QueryManipulatorComponent>> */
	private static array $registered = [];

	/**
	 * @param string $identifier
	 * @param class-string<QueryManipulatorComponent> $class
	 */
	public static function register(string $identifier, string $class) : void{
		self::$registered[$identifier] = $class;
	}

	public static function unregister(string $identifier) : void{
		unset(self::$registered[$identifier]);
	}

	/**
	 * @param string $identifier
	 * @param array<string, mixed> $configuration
	 * @return QueryManipulatorComponent
	 */
	public static function create(string $identifier, array $configuration) : QueryManipulatorComponent{
		return self::$registered[$identifier]::fromConfiguration($configuration);
	}
}