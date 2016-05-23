<?php

namespace Game\Map;

use Doctrine\ORM\Mapping as ORM;
use Game\Entities\User\User;
use Nette;



/**
 * @ORM\Entity(readOnly=TRUE)
 */
class Position extends \Teddy\Map\Position
{

	/**
	 * @ORM\OneToMany(targetEntity="Monster", mappedBy="position", indexBy="id", fetch="LAZY")
	 * @var Monster[]
	 */
	protected $monsters;

	/**
	 * @ORM\OneToMany(targetEntity="\Game\Entities\User\User", mappedBy="position", indexBy="id", fetch="LAZY")
	 * @var \Game\Entities\User\User[]
	 */
	protected $users;



	/**
	 * @return Monster[]
	 */
	public function getMonsters()
	{
		return $this->monsters;
	}



	/**
	 * Returns id for HTML attribute
	 * Converts ; to _, because semicolon is not valid character
	 *
	 * @return string
	 */
	public function getDivId()
	{
		return 'pos' . str_replace(';', '_', $this->getId());
	}



	/**
	 * @return float
	 */
	public function getWeight()
	{
		return $this->height < 0.15 ? 0 : round(pow($this->height + 1, 5), 3);
	}

}
