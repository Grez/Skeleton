<?php

namespace Game\Map;

use Game\Entities\User\User;
use Kdyby\Doctrine\Entities\Attributes\Identifier;
use Doctrine\ORM\Mapping as ORM;



/**
 * @ORM\Entity
 */
class Attack
{

	use Identifier;

	/**
	 * @ORM\ManyToOne(targetEntity="\Game\Entities\User\User")
	 * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
	 * @var User
	 */
	protected $user;

	/**
	 * @ORM\ManyToOne(targetEntity="\Game\Map\Monster")
	 * @ORM\JoinColumn(name="monster_id", referencedColumnName="id")
	 * @var Monster
	 */
	protected $monster;

	/**
	 * @ORM\Column(type="text")
	 * @var string
	 */
	protected $description;

	/**
	 * @ORM\Column(type="datetime")
	 * @var \DateTime
	 */
	protected $attackedAt;



	public function __construct(User $user, Monster $monster)
	{
		$this->attackedAt = new \DateTime();
		$this->user = $user;
		$this->monster = $monster;
	}



	/**
	 * @param string $description
	 * @return Attack
	 */
	public function setDescription($description)
	{
		$this->description = $description;
		return $this;
	}



	/**
	 * @return Monster
	 */
	public function getMonster()
	{
		return $this->monster;
	}



	/**
	 * @return string
	 */
	public function getDescription()
	{
		return $this->description;
	}



	/**
	 * @return \DateTime
	 */
	public function getAttackedAt()
	{
		return $this->attackedAt;
	}

}
