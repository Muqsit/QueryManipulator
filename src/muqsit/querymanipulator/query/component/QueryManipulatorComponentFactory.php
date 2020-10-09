<?php

declare(strict_types=1);

namespace muqsit\querymanipulator\query\component;

use pocketmine\utils\Utils;

final class QueryManipulatorComponentFactory{

	/**
	 * @var string[]|QueryManipulatorComponent[]
	 * @phpstan-var array<string, class-string<QueryManipulatorComponent>>
	 */
	private static $registered = [];

	public static function register(string $identifier, string $class) : void{
		Utils::testValidInstance($class, QueryManipulatorComponent::class);
		self::$registered[$identifier] = $class;
	}

	public static function unregister(string $identifier) : void{
		unset(self::$registered[$identifier]);
	}

	/**
	 * @param string $identifier
	 * @param mixed[] $configuration
	 * @return QueryManipulatorComponent
	 *
	 * @phpstan-param array<string, mixed> $configuration
	 */
	public static function create(string $identifier, array $configuration) : QueryManipulatorComponent{
		return self::$registered[$identifier]::fromConfiguration($configuration);
	}
}