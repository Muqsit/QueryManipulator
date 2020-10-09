<?php

declare(strict_types=1);

namespace muqsit\querymanipulator\query\component;

use Ds\Set;
use muqsit\querymanipulator\server\ServerQueryInfo;
use pocketmine\network\query\QueryInfo;

final class CombineSlotsQueryManipulatorComponent implements QueryManipulatorComponent{

	public static function fromConfiguration(array $configuration) : self{
		return new self($configuration["servers"], $configuration["max_slots"]);
	}

	/** @var Set<string> */
	private $server_identifiers;

	/** @var bool */
	private $manipulate_max;

	/** @var int[] */
	private $players = [];

	/** @var int[] */
	private $max_players = [];

	/**
	 * @param string[] $server_identifiers
	 * @param bool $manipulate_max
	 */
	public function __construct(array $server_identifiers, bool $manipulate_max){
		$this->server_identifiers = new Set($server_identifiers);
		$this->manipulate_max = $manipulate_max;
	}

	public function onReceiveUpdate(string $identifier, ServerQueryInfo $info) : void{
		if($this->server_identifiers->contains($identifier)){
			$this->players[$identifier] = $info->players;
			$this->max_players[$identifier] = $info->max_players;
		}
	}

	public function manipulate(QueryInfo $info) : void{
		if($this->manipulate_max){
			$info->setMaxPlayerCount(array_sum($this->max_players));
		}
		$info->setPlayerCount(array_sum($this->players));
	}
}