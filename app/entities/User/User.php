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
     * Edit your entity, you may add new properties...
     */

}
