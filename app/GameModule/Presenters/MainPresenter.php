<?php

namespace Game\GameModule\Presenters;

use Teddy\Map\Components\IMapControlFactory;
use Game\Map\Map;
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
		$map = $this->em->find(Map::class, 56);
		$startPosition = $this->em->find(Position::class, '56;-4;-4');
		return $this->mapControlFactory->create($map, $startPosition);
	}

}
