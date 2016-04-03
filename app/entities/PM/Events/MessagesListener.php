<?php

namespace Game\Entities\PM;

use Kdyby\Doctrine\EntityManager;
use Nette;
use Kdyby;
use Teddy\Entities\PM\Message;
use Teddy\Entities\PM\Messages;
use Teddy\Entities\User\User;
use Teddy\Websockets\ClientService;
use WebSocket\ConnectionException;



class MessagesListener extends \Teddy\Entities\PM\MessagesListener
{

	/**
	 * @var ClientService
	 */
	protected $clientService;



	public function __construct(ClientService $clientService, Messages $messageFacade, EntityManager $em)
	{
		parent::__construct($messageFacade, $em);
		$this->clientService = $clientService;
	}



	public function onNewMessage(Message $message)
	{
		$this->sendUnreadMessagesWS($message->getTo());
	}



	public function onReadMessage(Message $message)
	{
		$this->sendUnreadMessagesWS($message->getTo());
	}



	public function onUnreadMessage(Message $message)
	{
		$this->sendUnreadMessagesWS($message->getTo());
	}



	public function onDeleteMessage(Message $message, User $deletedBy)
	{
		$this->sendUnreadMessagesWS($deletedBy);
	}



	protected function sendUnreadMessagesWS(User $user)
	{
		$this->em->flush(); // we need flush because getUnreadMessagesCount() is asking db
		$this->messageFacade->getUnreadMessagesCount($user);

		try {
			$this->clientService->notifyUsers([$user->getId()], 'pm', $this->messageFacade->getUnreadMessagesCount($user));

		} catch (ConnectionException $e) {
			// log this?
		}
	}



	public function getSubscribedEvents()
	{
		return [
			'\Teddy\Entities\PM\Messages::onNewMessage',
			'\Teddy\Entities\PM\Messages::onReadMessage',
			'\Teddy\Entities\PM\Messages::onUnreadMessage',
			'\Teddy\Entities\PM\Messages::onDeleteMessage',
		];
	}

}
