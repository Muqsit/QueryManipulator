<?php

declare(strict_types=1);

namespace muqsit\querymanipulator\server;

final class FailedServerQueryInfo extends ServerQueryInfo{

	readonly public string $error;

	public function __construct(string $error){
		parent::__construct("", 0, 0);
		$this->error = $error;
	}

	public function __toString() : string{
		return "error: {$this->error}";
	}
}