<?php

namespace Game\Entities\User;

use Game\Map\Position;
use Nette\Security\Passwords;
use Nette\Utils\ArrayHash;



class Users extends \Teddy\Entities\User\Users
{

	/**
	 * @param ArrayHash $data
	 */
	public function register(ArrayHash $data)
	{
		$user = new User($data->email, $data->nick);

		$options = $this->salt ? ['salt' => $this->salt] : [];
		$hashed = Passwords::hash($data['password'], $options);
		$user->setPassword($hashed);

		$positionId = '1;' . mt_rand(-50, -20) . ';' . mt_rand(-50, -20);
		/** @var Position $position */
		$position = $this->em->find(Position::class, $positionId);
		$user->setPosition($position);
		$this->save($user);
	}

}
