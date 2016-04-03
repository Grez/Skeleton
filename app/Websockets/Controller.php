<?php

namespace Game\WebSockets;

use Ratchet\ConnectionInterface;
use Teddy\Entities\User\Users;



class Controller extends \Teddy\WebSockets\Controller
{

	/**
	 * @param ConnectionInterface $from
	 * @param int $userId
	 * @param string $apiKey
	 * @return bool
	 */
	protected function authorize(ConnectionInterface $from, $userId, $apiKey)
	{
		/** @var Users $usersFacade */
		$usersFacade = $this->container->getByType(Users::class);
		if (!$usersFacade->getByIdAndApikey($userId, $apiKey)) {
			return FALSE;
		}

		$this->users[$userId][$from->resourceId] = $from;
		$this->connections[$from->resourceId] = $userId;

		return TRUE;
	}

}
