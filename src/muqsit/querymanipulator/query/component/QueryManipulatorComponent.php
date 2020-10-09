<?php

declare(strict_types=1);

namespace muqsit\querymanipulator\query\component;

use muqsit\querymanipulator\server\ServerQueryInfo;
use pocketmine\network\query\QueryInfo;

interface QueryManipulatorComponent{

	/**
	 * @param array<string, mixed> $configuration
	 * @return static
	 */
	public static function fromConfiguration(array $configuration);

	public function onReceiveUpdate(string $identifier, ServerQueryInfo $info) : void;

	public function manipulate(QueryInfo $info) : void;
}