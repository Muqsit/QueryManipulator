<?php

declare(strict_types=1);

namespace muqsit\querymanipulator\query;

use Logger;
use muqsit\querymanipulator\query\component\QueryManipulatorComponent;
use muqsit\querymanipulator\server\ServerQueryInfo;
use pocketmine\network\query\QueryInfo;

final class QueryManipulator{

	/** @var Logger */
	private $logger;

	/** @var QueryManipulatorComponent[] */
	private $components = [];

	public function __construct(Logger $logger){
		$this->logger = $logger;
	}

	public function registerComponent(string $identifier, QueryManipulatorComponent $component) : void{
		$this->components[$identifier] = $component;
		$this->logger->debug("Registered component {$identifier}");
	}

	public function unregisterComponent(string $identifier) : void{
		unset($this->components[$identifier]);
		$this->logger->debug("Unregistered component {$identifier}");
	}

	/**
	 * @param ServerQueryInfo[] $server_query_infos
	 */
	public function update(array $server_query_infos) : void{
		foreach($server_query_infos as $identifier => $server_info){
			$this->logger->debug("Received update from server {$identifier}: {$server_info}");
			foreach($this->components as $component){
				$component->onReceiveUpdate($identifier, $server_info);
			}
		}
	}

	public function manipulate(QueryInfo $info) : void{
		foreach($this->components as $component){
			$component->manipulate($info);
		}
	}
}