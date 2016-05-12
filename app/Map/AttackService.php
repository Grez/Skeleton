<?php

namespace Game\Map;

use Game\Entities\User\User;
use Kdyby\Clock\IDateTimeProvider;
use Kdyby\Doctrine\EntityManager;



class AttackService extends \Nette\Object
{
	/**
	 * @var EntityManager
	 */
	protected $em;

	/**
	 * @var IDateTimeProvider
	 */
	protected $timeProvider;



	public function __construct(EntityManager $em, IDateTimeProvider $timeProvider)
	{
		$this->em = $em;
		$this->timeProvider = $timeProvider;
	}



	public function createAttack(User $user, Monster $monster)
	{
		$attack = new Attack($user, $monster);
		$description = '';
		for ($i = 1;; $i++) {
			$description .= '### Round #' . $i . "\n";
			$description .= 'Player: ' . $user->getCurrentHp() . '/' . $user->getMaxHp() . ' HP' . "\n";
			$description .= 'Monster: ' . $monster->getCurrentHp() . '/' . $monster->getMaxHp() . ' HP' . "\n";

			$userAttack = round($user->getAttack() * (mt_rand(0, 200) / 100));
			$monsterAttack = round($monster->getAttack() * (mt_rand(0, 200) / 100));

			$monster->setCurrentHp($monster->getCurrentHp() - $userAttack);
			$user->setCurrentHp($user->getCurrentHp() - $monsterAttack);

			$description .= 'Player took ' . $monsterAttack . ' damage' . "\n";
			$description .= 'Monster took ' . $userAttack . ' damage' . "\n";

			if ($user->getCurrentHp() < 0) {
				$user->setCurrentHp(1);
				$description .= 'Player dies' . "\n\n";
				break;
			}

			if ($monster->getCurrentHp() < 0) {
				$monster->setCurrentHp(0);
				$description .= 'Monster dies' . "\n\n";
				break;
			}

			$description .= "\n\n";
		}

		if ($monster->getCurrentHp() <= 0) {
			$experienceGained = $monster->getExperience();
			$user->addExperience($experienceGained);
			$description .= "Gained " . $experienceGained . " exp";
		}

		$attack->setDescription($description);
		$this->em->persist($attack);
		$this->em->flush();
	}
}
