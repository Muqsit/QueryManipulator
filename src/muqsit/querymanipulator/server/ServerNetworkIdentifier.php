<?php

declare(strict_types=1);

namespace muqsit\querymanipulator\server;

use InvalidArgumentException;
use function filter_var;
use function gethostbyname;
use const FILTER_FLAG_IPV4;
use const FILTER_VALIDATE_IP;

final class ServerNetworkIdentifier{

	public static function create(string $ip, int $port) : self{
		$ipv4 = filter_var(gethostbyname($ip), FILTER_VALIDATE_IP, FILTER_FLAG_IPV4);
		if($ipv4 === false){
			throw new InvalidArgumentException("Invalid IPv4 address {$ip}");
		}

		if($port < 0 || $port > 65535){
			throw new InvalidArgumentException("Invalid server port: {$port}");
		}
		return new self($ip, $port);
	}

	/**
	 * @param string $ip
	 * @param int<0, 65535> $port
	 */
	public function __construct(
		readonly public string $ip,
		readonly public int $port
	){}

	public function __toString() : string{
		return "ip: {$this->ip}, port: {$this->port}";
	}
}