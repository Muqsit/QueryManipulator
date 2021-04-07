<?php

declare(strict_types=1);

namespace muqsit\querymanipulator\server\task;

use Closure;
use libpmquery\PMQuery;
use libpmquery\PmQueryException;
use muqsit\querymanipulator\server\FailedServerQueryInfo;
use muqsit\querymanipulator\server\ServerNetworkIdentifier;
use muqsit\querymanipulator\server\ServerQueryInfo;
use pocketmine\scheduler\AsyncTask;

final class RetrieveServerQueryInfoTask extends AsyncTask{

	private const KEY_SERVER_INFO_CALLBACK = "server_info_callback";

	private string $server_network_identifiers_serialized;

	/**
	 * @param ServerNetworkIdentifier[] $server_network_identifiers
	 * @param Closure $callback
	 *
	 * @phpstan-param array<string, ServerNetworkIdentifier> $server_network_identifiers
	 * @phpstan-param Closure(array<string, ServerQueryInfo>) : void $callback
	 */
	public function __construct(array $server_network_identifiers, Closure $callback){
		$this->server_network_identifiers_serialized = igbinary_serialize($server_network_identifiers);
		$this->storeLocal(self::KEY_SERVER_INFO_CALLBACK, $callback);
	}

	public function onRun() : void{
		$result = [];
		/** @var array<string, ServerNetworkIdentifier> $server_network_identifiers */
		$server_network_identifiers = igbinary_unserialize($this->server_network_identifiers_serialized);

		foreach($server_network_identifiers as $id => $identifier){
			try{
				$query = PMQuery::query($identifier->ip, $identifier->port);
			}catch(PmQueryException $e){
				$result[$id] = new FailedServerQueryInfo($e->getMessage());
				continue;
			}

			$result[$id] = new ServerQueryInfo((string) $query["HostName"], (int) $query["Players"], (int) $query["MaxPlayers"]);
		}

		$this->setResult($result);
	}

	public function onCompletion() : void{
		/** @var Closure $callback */
		$callback = $this->fetchLocal(self::KEY_SERVER_INFO_CALLBACK);
		$callback($this->getResult());
	}
}