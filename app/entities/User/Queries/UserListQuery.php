<?php

namespace Game\Entities\User;

use Kdyby\Doctrine\QueryBuilder;



class UserListQuery extends \Teddy\Entities\User\UserListQuery
{

	/**
	 * @param string - ASC|DESC $order
	 * @return $this
	 */
	public function orderBylevel($order = 'DESC')
	{
		$this->select[] = function (QueryBuilder $qb) use ($order) {
			$qb->addOrderBy('u.level', $order);
		};
		return $this;
	}

}
