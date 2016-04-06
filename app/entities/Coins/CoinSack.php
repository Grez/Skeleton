<?php

namespace Game\Entities\Coins;

use Doctrine\ORM\Mapping as ORM;
use Kdyby\Doctrine\Entities\Attributes\Identifier;
use Nette;



/**
 * @ORM\Entity
 */
class CoinSack extends \Teddy\Entities\Coins\CoinSack
{

	use Identifier;

}
