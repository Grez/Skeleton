<?php

namespace Game\GameModule\Presenters;

use Game\GameModule\Components\IMapControlFactory;
use Nette\Caching\Cache;
use Nette\Caching\Storages\FileStorage;
use Teddy\GameModule\Presenters\BasePresenter;
use Game\Map\Map;
use Teddy\Map\MapService;
use Game\Map\Position;



class MainPresenter extends BasePresenter
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



	/**
	 * Renders .js file for map
	 */
	public function renderMapJs()
	{
		$seconds_to_cache = 86400;
		$ts = gmdate("D, d M Y H:i:s", time() + $seconds_to_cache) . " GMT";
		header("Expires: $ts");
		header("Pragma: cache");
		header("Cache-Control: max-age=$seconds_to_cache");
		header("Content-type: application/javascript");

		$cache = new Cache($this->storage);
		$value = $cache->load('map');
		if ($value === NULL) {
			$value = 'var map = '  . $this->mapService->getJsIncidenceMatrix(56) . ";\n\n";
			$cache->save('map', $value, [
				Cache::EXPIRE => '1 day',
			]);
		}
		echo $value;
		exit;
	}


	public function renderMapJsWorker()
	{
		header("Content-type: application/javascript");
		echo "var window = {};\n"; // we need this because of astar :|

		/** @var Map $map $map */
		$map = $this->em->find(Map::class, 56);
		$lastModified = $map->getPositionsLastModifiedAt() ? $map->getPositionsLastModifiedAt()->format('U') : 0;
		echo "importScripts('/js/teddy/map/astar.js', '/main/map-js/?lastModified=" . $lastModified . "');\n";

		echo "self.onmessage = function(e) {
            var data = e.data;
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
		$startPosition = $this->em->find(Position::class, '56;-10;-10');
		return $this->mapControlFactory->create($map, $startPosition);
	}

}
