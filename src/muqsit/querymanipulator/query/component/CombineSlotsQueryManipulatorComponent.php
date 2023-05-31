<?php

declare(strict_types=1);

namespace muqsit\querymanipulator\query\component;

use muqsit\querymanipulator\server\ServerQueryInfo;
use pocketmine\network\query\QueryInfo;
use function array_combine;
use function array_fill;
use function count;

final class CombineSlotsQueryManipulatorComponent implements QueryManipulatorComponent{

	public static function fromConfiguration(array $configuration) : self{
		/** @var array{servers: string[], max_slots: bool, exclude_self: bool} $configuration */
		return self::create($configuration["servers"], $configuration["max_slots"], $configuration["exclude_self"]);
	}

	public static function create(array $server_identifiers, bool $manipulate_max, bool $exclude_self) : self{
		return new self(array_combine($server_identifiers, array_fill(0, count($server_identifiers), true)), $manipulate_max, $exclude_self);
	}

	/** @var array<string, int> */
	private array $players = [];

	/** @var array<string, int> */
	private array $max_players = [];

	/**
	 * @param array<string, true> $server_identifiers
	 * @param bool $manipulate_max
	 * @param bool $exclude_self
	 */
	public function __construct(
		readonly private array $server_identifiers,
		readonly private bool $manipulate_max,
		readonly private bool $exclude_self
	){}

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