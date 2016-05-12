<?php

namespace Game\Entities\User;

use Doctrine\ORM\Mapping as ORM;
use Kdyby\Doctrine\Entities\Attributes\Identifier;
use Nette;



/**
 * @ORM\Entity
 */
class User extends \Teddy\Entities\User\User
{

	use Identifier;

	/**
	 * @ORM\ManyToOne(targetEntity="\Game\Map\Position", inversedBy="users")
	 * @var \Game\Map\Position
	 */
	protected $position;

	/**
	 * @ORM\Column(type="integer", nullable=FALSE)
	 * @var int
	 */
	protected $moves;

	/**
	 * @ORM\Column(type="integer", nullable=FALSE)
	 * @var int
	 */
	protected $level;

	/**
	 * @ORM\Column(type="integer", nullable=FALSE)
	 * @var int
	 */
	protected $experience;

	/**
	 * @ORM\Column(type="integer", nullable=FALSE)
	 * @var int
	 */
	protected $currentHp;



	/**
	 * @param string $email
	 * @param string $nick
	 * @param string $password
	 */
	public function __construct($email, $nick = '', $password = '')
	{
		parent::__construct($email, $nick, $password);
		$this->moves = 1440;
		$this->currentHp = $this->getMaxHp();
		$this->updateLevelFromExperience();
	}



	/**
	 * @return \Game\Map\Position
	 */
	public function getPosition()
	{
		return $this->position;
	}



	/**
	 * @param \Game\Map\Position $position
	 * @return User
	 */
	public function setPosition(\Game\Map\Position $position)
	{
		$this->position = $position;
		return $this;
	}



	/**
	 * @return int
	 */
	public function getMoves()
	{
		return $this->moves;
	}



	/**
	 * @param int $moves
	 * @return User
	 */
	public function setMoves($moves)
	{
		$this->moves = $moves;
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
	 * @param int $currentHp
	 * @return User
	 */
	public function setCurrentHp($currentHp)
	{
		$this->currentHp = $currentHp;
		return $this;
	}



	/**
	 * @return int
	 */
	public function getMaxHp()
	{
		return $this->level * 100;
	}



	/**
	 * @return int
	 */
	public function getLevel()
	{
		return $this->level;
	}



	/**
	 * @param int $level
	 * @return User
	 */
	public function setLevel($level)
	{
		$this->level = $level;
		return $this;
	}



	/**
	 * @return float
	 */
	public function getHpPercentage()
	{
		return round($this->getCurrentHp() / $this->getMaxHp() * 100);
	}



	/**
	 * @return int
	 */
	public function getAttack()
	{
		return $this->level * 20;
	}



	/**
	 * @return int
	 */
	public function getExperience()
	{
		return $this->experience;
	}



	/**
	 * @param int $experience
	 * @return User
	 */
	public function addExperience($experience)
	{
		$this->experience += $experience;
		$this->updateLevelFromExperience();
		return $this;
	}



	/**
	 * Sets level by experience
	 */
	private function updateLevelFromExperience()
	{
		if ($this->experience >= 100 && $this->experience < 250) {
			$this->level = 2;

		} else if ($this->experience >= 250 && $this->experience < 1000) {
			$this->level = 3;

		} else if ($this->experience >= 1000 && $this->experience < 2500) {
			$this->level = 4;

		} else if ($this->experience >= 2500 && $this->experience < 10000) {
			$this->level = 5;

		} else {
			$this->level = 1;
		}
	}

}
