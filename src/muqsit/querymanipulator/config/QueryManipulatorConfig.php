<?php

declare(strict_types=1);

namespace muqsit\querymanipulator\config;

final class QueryManipulatorConfig{

	/**
	 * @param array<string, mixed> $data
	 * @return self
	 */
	public static function jsonDeserialize(array $data) : self{
		/** @var array{
		 * 		update_interval: positive-int,
		 * 		server_identifiers: array<string, array{ip: string, port: positive-int}>,
		 *		components: array<string, array<string, mixed>>
		 *	} $data */

		$update_interval = $data["update_interval"];

		$server_identifiers = [];
		foreach($data["server_identifiers"] as $identifier => ["ip" => $ip, "port" => $port]){
			$server_identifiers[] = new QueryManipulatorConfigServerIdentifier($identifier, $ip, $port);
		}

		return new self($update_interval, $server_identifiers, $data["components"]);
	}

	/**
	 * @param int $update_interval
	 * @param QueryManipulatorConfigServerIdentifier[] $server_identifiers
	 * @param array<string, array<string, mixed>> $components
	 */
	public function __construct(
		readonly public int $update_interval,
		readonly public array $server_identifiers,
		readonly public array $components
	){}
}