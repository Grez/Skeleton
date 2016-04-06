<?php

namespace Game\Entities\User;

use Doctrine\ORM\Mapping as ORM;
use Kdyby\Doctrine\Entities\Attributes\Identifier;
use Nette;



/**
 * @ORM\Entity
 */
class UserAgent extends \Teddy\Entities\User\UserAgent
{

	use Identifier;

}
