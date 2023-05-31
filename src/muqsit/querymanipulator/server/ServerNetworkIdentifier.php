<?php

declare(strict_types=1);

namespace muqsit\querymanipulator\server;

use InvalidArgumentException;

final class ServerNetworkIdentifier{

	readonly public string $ip;
	readonly public int $port;

	public function __construct(string $ip, int $port){
		$ipv4 = filter_var(gethostbyname($ip), FILTER_VALIDATE_IP, FILTER_FLAG_IPV4);
		if($ipv4 === false){
			throw new InvalidArgumentException("Invalid IPv4 address {$ip}");
		}
		$this->ip = $ipv4;

		if($port < 0 || $port > 0xffff){
			throw new InvalidArgumentException("Invalid server port: {$port}");
		}
		$this->port = $port;
	}

	public function __toString() : string{
		return "ip: {$this->ip}, port: {$this->port}";
	}
}