<?php

declare(strict_types=1);

namespace muqsit\querymanipulator;

use muqsit\querymanipulator\query\QueryManipulator;
use pocketmine\event\Listener;
use pocketmine\event\server\QueryRegenerateEvent;

final class EventHandler implements Listener{

	/** @var QueryManipulator */
	private $manipulator;

	public function __construct(QueryManipulator $manipulator){
		$this->manipulator = $manipulator;
	}

	/**
	 * @param QueryRegenerateEvent $event
	 * @priority NORMAL
	 */
	public function onQueryRegenerate(QueryRegenerateEvent $event) : void{
		$this->manipulator->manipulate($event->getQueryInfo());
	}
}