<?php

namespace Game\Map\Components;

use Game\Map\MapService;
use Game\Map\Monster;
use Kdyby\Doctrine\EntityManager;
use Kdyby\Doctrine\ResultSet;
use Nette\Utils\ArrayHash;
use Teddy;
use Game\Map\Map;
use Game\Map\Position;
use Teddy\Forms\Form;



/**
 * @method void onMovementSuccess(MapControl $this, \Game\Entities\User\User $user, string $result)
 * @method void onMovementError(MapControl $this, \Game\Entities\User\User $user, string $error)
 */
class MapControl extends Teddy\Map\Components\MapControl
{

	/**
	 * @var array
	 */
	public $onMovementSuccess = [];

	/**
	 * @var array
	 */
	public $onMovementError = [];

	/**
	 * @var ResultSet
	 */
	private $monsters = [];

	/**
	 * @var ResultSet
	 */
	private $players = [];

	/**
	 * @var Teddy\Security\User
	 */
	private $user;

	/**
	 * @var EntityManager
	 */
	private $em;

	/**
	 * @var MapService
	 */
	private $mapService;



	public function __construct(Teddy\Security\User $user, MapService $mapService, EntityManager $em, Map $map, ResultSet $monsters, ResultSet $players)
	{
		$this->map = $map;
		$this->monsters = $monsters;
		$this->players = $players;
		$this->user = $user;
		$this->em = $em;
		$this->mapService = $mapService;
	}



	/**
	 * Moves player to position
	 *
	 * @param Form $form
	 * @param ArrayHash $values
	 */
	public function moveFormSuccess(Form $form, ArrayHash $values)
	{
		$positionsFromForm = explode(',', $values->positions);

		/** @var Position[] $positions */
		$positions = $this->em->getRepository(Position::class)->findBy([
			'id IN' => $positionsFromForm,
		]);

		if ($this->user->getEntity()->isWounded()) {
			$this->onMovementError($this, $this->user->getEntity(), 'You are too wounded for moving. Wait few minutes before you regenerate.');
			$form['positions']->setValue(NULL);
			$this->redrawControl();
			return;
		}

		$orderedPositions = [];
		$currentPosition = $this->user->getEntity()->getPosition();
		for ($i = 0; $i < count($positionsFromForm); $i++) {
			foreach ($positions as $key => $position) {
				if ($currentPosition->isNeighbour($position)) {
					$orderedPositions[] = $position;
					$currentPosition = $position;
					unset($positions[$key]);
					break;
				}
			}
		}

		// Probably just started from wrong position
		if (count($orderedPositions) !== count($positionsFromForm)) {
			$this->onMovementError($this, $this->user->getEntity(), 'Ouch something went wrong. Please try again.');
			$form['positions']->setValue(NULL);
			$this->redrawControl();
			return;
		}

		try {
			$attackedMonsters = $this->mapService->movePlayer($this->user->getEntity(), $orderedPositions);

		} catch (\InvalidArgumentException $e) {
			$this->onMovementError($this, $this->user->getEntity(), $e->getMessage());
			$this->redrawControl();
			return;
		}

		$result = 'You have moved to location.';
		if ($attackedMonsters) {
			$names = array_map(function (Monster $monster) {
				return $monster->getName();
			}, $attackedMonsters);
			$result = 'You have moved to location and attacked monster ' . implode(', ', $names);
		}

		$this->onMovementSuccess($this, $this->user->getEntity(), $result);
		$form['positions']->setValue(NULL);
		$this->redrawControl();
	}



	protected function createComponentMoveForm()
	{
		$form = new Form();
		$form->addHidden('positions')
			->setRequired('You have to move somewhere');
		$form->addText('cost', 'Cost')
			->setDisabled();
		$form->addSubmit('move', 'Move');
		$form->onSuccess[] = $this->moveFormSuccess;
		return $form->setBootstrapRenderer();
	}



	public function render()
	{
		$template = parent::createTemplate();
		$template->map = $this->map;
		$template->startPosition = $this->startPosition;
		$template->renderMap = $this->renderMap;
		$template->monsters = $this->monsters;
		$template->players = $this->players;
		$template->setFile(__DIR__ . '/map.latte');
		$template->render();
	}

}



interface IMapControlFactory
{

	/**
	 * @param Map $map
	 * @param ResultSet $monsters
	 * @param ResultSet $players
	 * @return MapControl
	 */
	public function create(Map $map, ResultSet $monsters, ResultSet $players);
}
