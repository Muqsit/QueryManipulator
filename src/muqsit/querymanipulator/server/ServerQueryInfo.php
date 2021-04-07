<?php

declare(strict_types=1);

namespace muqsit\querymanipulator\server;

class ServerQueryInfo{

	public string $host_name;
	public int $players;
	public int $max_players;

	public function __construct(string $host_name, int $players, int $max_players){
		$this->host_name = $host_name;
		$this->players = $players;
		$this->max_players = $max_players;
	}

	public function __toString() : string{
		return "host_name: {$this->host_name}, players: {$this->players}, max_players: {$this->max_players}";
	}
}