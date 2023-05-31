<?php

declare(strict_types=1);

namespace muqsit\querymanipulator;

use muqsit\querymanipulator\query\QueryManipulator;
use pocketmine\event\Listener;
use pocketmine\event\server\QueryRegenerateEvent;

final class EventHandler implements Listener{

	public function __construct(
		readonly private QueryManipulator $manipulator
	){}

	/**
	 * @param QueryRegenerateEvent $event
	 * @priority NORMAL
	 */
	public function onQueryRegenerate(QueryRegenerateEvent $event) : void{
		$this->manipulator->manipulate($event->getQueryInfo());
	}
}