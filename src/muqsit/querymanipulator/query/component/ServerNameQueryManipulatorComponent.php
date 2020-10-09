<?php

declare(strict_types=1);

namespace muqsit\querymanipulator\query\component;

use muqsit\querymanipulator\server\ServerQueryInfo;
use pocketmine\network\query\QueryInfo;

final class ServerNameQueryManipulatorComponent implements QueryManipulatorComponent{

	public static function fromConfiguration(array $configuration) : self{
		return new self($configuration["server"]);
	}

	/** @var string */
	private $server_identifier;

	/** @var string */
	private $name = "";

	public function __construct(string $server_identifier){
		$this->server_identifier = $server_identifier;
	}

	public function onReceiveUpdate(string $identifier, ServerQueryInfo $info) : void{
		if($identifier === $this->server_identifier){
			$this->name = $info->host_name;
		}
	}

	public function manipulate(QueryInfo $info) : void{
		$info->setServerName($this->name);
	}
}