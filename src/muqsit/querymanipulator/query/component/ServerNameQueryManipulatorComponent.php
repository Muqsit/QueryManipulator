<?php

declare(strict_types=1);

namespace muqsit\querymanipulator\query\component;

use muqsit\querymanipulator\server\ServerQueryInfo;
use pocketmine\network\query\QueryInfo;

final class ServerNameQueryManipulatorComponent implements QueryManipulatorComponent{

	public static function fromConfiguration(array $configuration) : self{
		/** @var array{server: string} $configuration */
		return new self($configuration["server"]);
	}

	private string $name = "";

	public function __construct(
		readonly private string $server_identifier
	){}

	public function onReceiveUpdate(string $identifier, ServerQueryInfo $info) : void{
		if($identifier === $this->server_identifier){
			$this->name = $info->host_name;
		}
	}

	public function manipulate(QueryInfo $info) : void{
		$info->setServerName($this->name);
	}
}