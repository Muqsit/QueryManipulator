<?php

declare(strict_types=1);

namespace muqsit\querymanipulator\server;

final class FailedServerQueryInfo extends ServerQueryInfo{

	public function __construct(
		readonly public string $error
	){
		parent::__construct("", 0, 0);
	}

	public function __toString() : string{
		return "error: {$this->error}";
	}
}