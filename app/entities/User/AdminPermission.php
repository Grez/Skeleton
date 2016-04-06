<?php

namespace Game\Entities\User;

use Doctrine\ORM\Mapping as ORM;
use Kdyby\Doctrine\Entities\Attributes\Identifier;
use Nette;



/**
 * @ORM\Entity
 */
class AdminPermission extends \Teddy\Entities\User\AdminPermission
{

	use Identifier;

}
