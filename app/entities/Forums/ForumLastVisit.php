<?php

namespace Game\Entities\Forums;

use Kdyby\Doctrine\Entities\Attributes\Identifier;
use Doctrine\ORM\Mapping as ORM;



/**
 * @ORM\Entity
 */
class ForumLastVisit extends \Teddy\Entities\Forums\ForumLastVisit
{

	use Identifier;

}
