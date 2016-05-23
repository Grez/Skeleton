<?php

namespace Game\GameModule\Presenters;

use Game\Map\Attack;



class AttacksPresenter extends BasePresenter
{

	public function renderDefault()
	{
		$this->template->attacks = $this->em->getRepository(Attack::class)->findBy([
			'user' => $this->user,
		], ['id' => 'DESC']);
	}

}
