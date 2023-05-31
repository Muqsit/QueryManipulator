<?php

declare(strict_types=1);

namespace muqsit\querymanipulator\query\component;

use muqsit\querymanipulator\server\ServerQueryInfo;
use pocketmine\network\query\QueryInfo;

final class CombineSlotsQueryManipulatorComponent implements QueryManipulatorComponent{

	public static function fromConfiguration(array $configuration) : self{
		/** @var array{servers: string[], max_slots: bool, exclude_self: bool} $configuration */
		return new self($configuration["servers"], $configuration["max_slots"], $configuration["exclude_self"]);
	}

	readonly private bool $manipulate_max;
	readonly private bool $exclude_self;

	/** @var string[] */
	private array $server_identifiers = [];

	/** @var int[] */
	private array $players = [];

	/** @var int[] */
	private array $max_players = [];

	/**
	 * @param string[] $server_identifiers
	 * @param bool $manipulate_max
	 * @param bool $exclude_self
	 */
	public function __construct(array $server_identifiers, bool $manipulate_max, bool $exclude_self){
		$this->exclude_self = $exclude_self;
		$this->manipulate_max = $manipulate_max;
		foreach($server_identifiers as $server_identifier){
			$this->server_identifiers[$server_identifier] = $server_identifier;
		}
	}

	public function onReceiveUpdate(string $identifier, ServerQueryInfo $info) : void{
		if(isset($this->server_identifiers[$identifier])){
			$this->players[$identifier] = $info->players;
			$this->max_players[$identifier] = $info->max_players;
		}
	}

	public function manipulate(QueryInfo $info) : void{
		if($this->manipulate_max){
			$info->setMaxPlayerCount(array_sum($this->max_players));
		}
		$info->setPlayerCount(($this->exclude_self ? 0 : $info->getPlayerCount()) + array_sum($this->players));
	}
}