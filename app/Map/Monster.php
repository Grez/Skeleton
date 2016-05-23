<?php

namespace Game\Map;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Kdyby\Doctrine\Entities\Attributes\Identifier;



/**
 * @ORM\Entity()
 */
class Monster
{

	use Identifier;

	/**
	 * @ORM\ManyToOne(targetEntity="Position", inversedBy="monsters", fetch="EAGER")
	 * @var Position
	 */
	protected $position;

	/**
	 * @ORM\Column(type="string", nullable=FALSE)
	 * @var string
	 */
	protected $name;

	/**
	 * @ORM\Column(type="integer", nullable=FALSE)
	 * @var int
	 */
	protected $level;

	/**
	 * @ORM\Column(type="integer", nullable=FALSE)
	 * @var int
	 */
	protected $currentHp;



	public function __construct($position, $name, $level)
	{
		$this->position = $position;
		$this->name = $name;
		$this->level = $level;
		$this->currentHp = $this->getMaxHp();
	}



	/**
	 * @return int
	 */
	public function getMaxHp()
	{
		return $this->level * 10;
	}



	/**
	 * return int
	 */
	public function getAttack()
	{
		return $this->level * 5;
	}



	/**
	 * @return int
	 */
	public function getLevel()
	{
		return $this->level;
	}



	/**
	 * @return string
	 */
	public function getName()
	{
		return $this->name . ' monster #' . $this->getId();
	}



	/**
	 * @return Position
	 */
	public function getPosition()
	{
		return $this->position;
	}



	/**
	 * @param int $currentHp
	 * @return Monster
	 */
	public function setCurrentHp($currentHp)
	{
		$this->currentHp = $currentHp;
		return $this;
	}



	/**
	 * @return int
	 */
	public function getCurrentHp()
	{
		return $this->currentHp;
	}



	/**
	 * @return int
	 */
	public function getExperience()
	{
		return pow($this->level, 2) * mt_rand(5, 10);
	}

}
