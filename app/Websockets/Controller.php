<?php

namespace Game\WebSockets;

use Game\Entities\Chat\ChatFacade;
use Ratchet\ConnectionInterface;
use Teddy\Entities\User\Users;



class Controller extends \Teddy\WebSockets\Controller
{

	/**
	 * @var string[]
	 */
	protected $customMethods = [
		'addNewChatMessage',
	];



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


	/***** CHAT *****/


	/**
	 * @param ConnectionInterface $from
	 * @param \stdClass $data
	 */
	protected function addNewChatMessage(ConnectionInterface $from, $data)
	{
		echo 'Sending chat message from #' . $from->resourceId . "\n";

		/** @var ChatFacade $chatFacade */
		$chatFacade = $this->container->getByType(ChatFacade::class);
		$chatMessage = $chatFacade->addPost($this->getUserId($from), $data->message);
		$data->user = $chatMessage->getAuthor()->getNick();
		$data->postedAt = $chatMessage->getPostedAt()->format('Y-m-d H:i:s');
		$this->updateChat($from, $data);
	}



	/**
	 * @param ConnectionInterface $from
	 * @param \stdClass $data
	 */
	protected function updateChat(ConnectionInterface $from, $data)
	{
		$content = new \stdClass();
		$content->component = 'chat';
		$content->data = $data;
		$json = json_encode($content);
		$this->sendToEveryoneIncludingMe($from, $json);
	}

}
