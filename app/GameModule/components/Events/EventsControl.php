<?php

namespace Game\GameModule\Components;

use Game\Map\Attack;
use Teddy\Security\User;
use Kdyby\Doctrine\EntityManager;
use Teddy;
use Teddy\Entities\PM\Messages;



class EventsControl extends Teddy\GameModule\Components\EventsControl
{

	/**
	 * @var EntityManager
	 */
	private $em;

	/**
	 * @var int
	 */
	private $attacks;



	public function __construct(User $user, Messages $messagesFacade, EntityManager $em)
	{
		parent::__construct($user, $messagesFacade);
		$this->em = $em;
		$this->attacks = $this->em->getRepository(Attack::class)->countBy([
			'user' => $user->getEntity()
		]);
	}



	public function render()
	{
		$template = parent::createTemplate();
		$template->setFile(__DIR__ . '/events.latte');
		$template->unreadMessages = $this->unreadMessages;
		$template->notifications = $this->notifications;
		$template->attacks = $this->attacks;
		$template->events = $this->getNumberOfEvents();
		$template->render();
	}

}



interface IEventsControlFactory
{

	/** @return EventsControl */
	function create();
}
