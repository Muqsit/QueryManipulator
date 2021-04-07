<?php

declare(strict_types=1);

namespace muqsit\querymanipulator\config;

final class QueryManipulatorConfigServerIdentifier{

	public string $identifier;
	public string $ip;
	public int $port;

	public function __construct(string $identifier, string $ip, int $port){
		$this->identifier = $identifier;
		$this->ip = $ip;
		$this->port = $port;
	}
}