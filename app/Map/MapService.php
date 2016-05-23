<?php

namespace Game\Map;

use Game\Entities\User\User;
use Kdyby\Clock\IDateTimeProvider;
use Kdyby\Doctrine\EntityManager;
use Nette;



class MapService extends \Teddy\Map\MapService
{

	/**
	 * @var AttackService
	 */
	protected $attackService;



	public function __construct(EntityManager $em, IDateTimeProvider $timeProvider, AttackService $attackService)
	{
		parent::__construct($em, $timeProvider);
		$this->attackService = $attackService;
	}


	public function generateMonsters(Map $map, $monsters, $level)
	{
		$names = [
			'Terrifying',
			'Spooky',
			'Scary',
			'Chilling',
			'Creepy',
			'Frightening',
		];

		for ($i = 0; $i < $monsters; $i++) {
			$name = $names[array_rand($names)];

			$max = $map->getRadius() - 1;
			$min = $max * -1;
			$x = mt_rand($min, $max);
			$y = mt_rand($min, $max);
			$position = $map->getPosition($x, $y);
			$monster = new Monster($position, $name, $level);
			$this->em->persist($monster);
			$this->em->flush($monster);
//			$position->addMonster($monster);
		}
	}



	/**
	 * Moves User through positions
	 *
	 * @param User $user
	 * @param Position[] $path
	 * @return Monster[]
	 * @throws \InvalidArgumentException
	 */
	public function movePlayer(User $user, $path)
	{
		$attackedMonsters = [];

		if (!$this->isPathValid($user->getPosition(), $path)) {
			throw new \InvalidArgumentException('Invalid path (You are skipping tiles)');
		}

		$pathWeight = $this->getPathWeight($path);
		if ($user->getMoves() < $pathWeight) {
			throw new \InvalidArgumentException('You can\'t move that far');
		}

		$user->setPosition($this->getPathDestination($path));
		$user->setMoves($user->getMoves() - $pathWeight);

		foreach ($this->getPathDestination($path)->getMonsters() as $monster) {
			$this->attackService->createAttack($user, $monster);
			$attackedMonsters[] = $monster;
		}
		$this->em->flush();
		return $attackedMonsters;
	}

}
