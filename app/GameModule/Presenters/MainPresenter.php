<?php

namespace Game\GameModule\Presenters;

use Game\GameModule\Components\IMapControlFactory;
use Teddy\GameModule\Presenters\BasePresenter;
use Teddy\Map\Map;
use Teddy\Map\Position;



class MainPresenter extends BasePresenter
{

	/**
	 * @var IMapControlFactory
	 * @inject
	 */
	public $mapControlFactory;



	protected function createComponentMap()
	{
		$map = $this->em->find(Map::class, 56);
		$startPosition = $this->em->find(Position::class, '56;-37;-34');
		return $this->mapControlFactory->create($map, $startPosition);
	}

}
