<?php

declare(strict_types=1);

namespace muqsit\querymanipulator\config;

final class QueryManipulatorConfigServerIdentifier{

	public function __construct(
		readonly public string $identifier,
		readonly public string $ip,
		readonly public int $port
	){}
}