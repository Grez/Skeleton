<?php

namespace Game\GameModule\Presenters;

use Doctrine\ORM\AbstractQuery;
use Game\Entities\User\User;
use Game\Map\Components\IMapControlFactory;
use Game\Map\Components\MapControl;
use Game\Map\Map;
use Game\Map\Monster;
use Game\Map\MonstersQuery;
use Teddy\Entities\User\UserListQuery;
use Teddy\Map\MapService;
use Game\Map\Position;



class MainPresenter extends \Teddy\GameModule\Presenters\MainPresenter
{

	/**
	 * @var IMapControlFactory
	 * @inject
	 */
	public $mapControlFactory;

	/**
	 * @var MapService
	 * @inject
	 */
	public $mapService;

	/**
	 * @var \Nette\Caching\IStorage
	 * @inject
	 */
	public $storage;



	public function renderMapJsWorker()
	{
		header("Content-type: application/javascript");
		echo "var window = {};\n"; // we need this because of astar :|
		echo "importScripts('/js/teddy/map/astar.js');\n";

		echo "self.onmessage = function(e) {
            var data = e.data;
            var map = new window.Graph(JSON.parse(data.map));
            var start = map.grid[data.start[0]][data.start[1]];
			var end = map.grid[data.target[0]][data.target[1]];
			var result = window.astar.search(map, start, end);

			self.postMessage(result);
		}";
		exit;
	}



	protected function createComponentMap()
	{
		$map = $this->em->find(Map::class, 1);
		$query = (new MonstersQuery())->onlyLiving();
		$monsters = $this->em->getRepository(Monster::class)->fetch($query);

		$query = (new UserListQuery())->onlyUndeleted()->excludeUser($this->user);
		$players = $this->em->getRepository(User::class)->fetch($query);

		$control = $this->mapControlFactory->create($map, $monsters, $players);
		$control->onMovementSuccess[] = function (MapControl $mapControl, \Game\Entities\User\User $user, $result) {
			$this->successFlashMessage($result);
			$this->redrawControl();
		};
		$control->onMovementError[] = function (MapControl $mapControl, \Game\Entities\User\User $user, $error) {
			$this->warningFlashMessage($error);
			$this->redrawControl();
		};
		return $control;
	}

}
