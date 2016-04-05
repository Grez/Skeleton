<?php

namespace Game\GameModule\Presenters;

use Teddy\Entities\User\User;



class UserPresenter extends \Teddy\GameModule\Presenters\UserPresenter
{
	public function renderDefault()
	{
		parent::renderDefault();
		$user = $this->em->find(User::class, 1);
		\Tracy\Debugger::barDump($user);
//		\Tracy\Debugger::barDump($user->getSayWut());
	}
}
