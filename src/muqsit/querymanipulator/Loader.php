<?php

declare(strict_types=1);

namespace muqsit\querymanipulator;

use Closure;
use http\Exception\RuntimeException;
use muqsit\querymanipulator\config\QueryManipulatorConfig;
use muqsit\querymanipulator\query\component\CombineSlotsQueryManipulatorComponent;
use muqsit\querymanipulator\query\component\QueryManipulatorComponentFactory;
use muqsit\querymanipulator\query\component\ServerNameQueryManipulatorComponent;
use muqsit\querymanipulator\query\QueryManipulator;
use muqsit\querymanipulator\server\ServerNetworkIdentifier;
use muqsit\querymanipulator\server\ServerQueryInfo;
use muqsit\querymanipulator\server\task\RetrieveServerQueryInfoTask;
use pocketmine\plugin\PluginBase;
use pocketmine\scheduler\ClosureTask;

final class Loader extends PluginBase{

	/** @var ServerNetworkIdentifier[] */
	private array $servers = [];

	private QueryManipulator $manipulator;

	protected function onLoad() : void{
		$this->manipulator = new QueryManipulator($this->getLogger());

		QueryManipulatorComponentFactory::register("combine_slots", CombineSlotsQueryManipulatorComponent::class);
		QueryManipulatorComponentFactory::register("server_name", ServerNameQueryManipulatorComponent::class);

		$this->saveResource("config.json");
		foreach($this->getConfiguration()->server_identifiers as $info){
			$this->registerServer($info->identifier, $server = new ServerNetworkIdentifier($info->ip, $info->port));
		}
	}

	protected function onEnable() : void{
		$config = $this->getConfiguration();
		foreach($config->components as $identifier => $configuration){
			$this->manipulator->registerComponent($identifier, QueryManipulatorComponentFactory::create($identifier, $configuration));
		}

		$this->getServer()->getPluginManager()->registerEvents(new EventHandler($this->manipulator), $this);

		$this->getScheduler()->scheduleRepeatingTask(new ClosureTask(function() : void{
			$this->updateServerQueryInfo();
		}), $config->update_interval);
	}

	private function getConfiguration() : QueryManipulatorConfig{
		$path = $this->getDataFolder() . "config.json";
		$contents = file_get_contents($path);
		if($contents === false){
			throw new RuntimeException("Failed to read file {$path}");
		}

		$data = json_decode($contents, true, 512, JSON_THROW_ON_ERROR);
		if(!is_array($data)){
			throw new RuntimeException("Failed to parse configuration ({$path}) due to invalid structure");
		}
		return QueryManipulatorConfig::jsonDeserialize($data);
	}

	public function getManipulator() : QueryManipulator{
		return $this->manipulator;
	}

	public function registerServer(string $identifier, ServerNetworkIdentifier $info) : void{
		$this->servers[$identifier] = $info;
		$this->getLogger()->debug("Registered server {$identifier} [{$info}]");
	}

	public function updateServerQueryInfo() : void{
		$this->requestServerUpdate(function(array $server_query_infos) : void{
			$this->manipulator->update($server_query_infos);
		});
	}

	/**
	 * @param Closure(array<string, ServerQueryInfo>) : void $callback
	 */
	public function requestServerUpdate(Closure $callback) : void{
		$this->getServer()->getAsyncPool()->submitTask(new RetrieveServerQueryInfoTask($this->servers, $callback));
	}
}