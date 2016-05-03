<?php

namespace Game\Entities\Chat;

use Game\Entities\User\User;
use Kdyby\Clock\IDateTimeProvider;
use Kdyby\Doctrine\Entities\Attributes\Identifier;
use Doctrine\ORM\Mapping as ORM;
use Kdyby\Doctrine\EntityManager;
use Teddy\Entities\Manager;



class ChatFacade extends Manager
{

	/**
	 * @var IDateTimeProvider
	 */
	protected $timeProvider;



	public function __construct(EntityManager $em, IDateTimeProvider $timeProvider)
	{
		parent::__construct($em);
		$this->timeProvider = $timeProvider;
	}



	public function getPostsSince()
	{

	}



	/**
	 * @param int $userId
	 * @param string $message
	 * @return ChatMessage
	 */
	public function addPost($userId, $message)
	{
		/** @var User $user */
		$user = $this->em->find(User::class, $userId);
		if (!$user) {
			throw new \InvalidArgumentException('Unknown user id');
		}

		$message = new ChatMessage($user, $message);
		$this->em->persist($message)->flush();
		return $message;
	}

}
