<?php

namespace Game\Entities\Chat;

use Game\Entities\User\User;
use Kdyby\Doctrine\Entities\Attributes\Identifier;
use Doctrine\ORM\Mapping as ORM;



/**
 * @ORM\Entity
 */
class ChatMessage
{

	use Identifier;

	/**
	 * @ORM\Column(type="text")
	 * @var string
	 */
	protected $message;

	/**
	 * @ORM\ManyToOne(targetEntity="\Game\Entities\User\User")
	 * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
	 * @var User
	 */
	protected $author;

	/**
	 * @ORM\Column(type="datetime")
	 * @var \DateTime
	 */
	protected $postedAt;



	public function __construct(User $author, $message)
	{
		$this->postedAt = new \DateTime();
		$this->message = $message;
		$this->author = $author;
	}



	/**
	 * @return User
	 */
	public function getAuthor()
	{
		return $this->author;
	}



	/**
	 * @return string
	 */
	public function getMessage()
	{
		return $this->message;
	}



	/**
	 * @return \DateTime
	 */
	public function getPostedAt()
	{
		return $this->postedAt;
	}



	/**
	 * @param User $user
	 * @return bool
	 */
	public function canDelete(User $user)
	{
		return $user->isAdmin();
	}

}
