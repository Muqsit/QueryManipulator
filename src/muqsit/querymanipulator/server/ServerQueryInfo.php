<?php

declare(strict_types=1);

namespace muqsit\querymanipulator\server;

class ServerQueryInfo{
	public function __construct(
		readonly public string $host_name,
		readonly public int $players,
		readonly public int $max_players
	){}

	public function __toString() : string{
		return "host_name: {$this->host_name}, players: {$this->players}, max_players: {$this->max_players}";
	}
}