<?php

namespace Game\Map;

use Doctrine\ORM\QueryBuilder;
use Game\Entities\User\User;
use Kdyby\Persistence\Queryable;


class MonstersQuery extends \Kdyby\Doctrine\QueryObject
{

	/**
	 * @var array|\Closure[]
	 */
	private $filter = [];

	/**
	 * @var array|\Closure[]
	 */
	private $select = [];



	/**
	 * @return $this
	 */
	public function onlyLiving()
	{
		$this->filter[] = function (QueryBuilder $qb) {
			$qb->andWhere('m.currentHp > 0');
		};
		return $this;
	}


	/**
	 * @param Queryable $repository
	 * @return \Doctrine\ORM\Query|\Doctrine\ORM\QueryBuilder
	 */
	protected function doCreateQuery(Queryable $repository)
	{
		$qb = $this->createBasicDql($repository)
			->addSelect('p')
			->innerJoin('m.position', 'p');

		foreach ($this->select as $modifier) {
			$modifier($qb);
		}

		return $qb;
	}



	/**
	 * @param Queryable $repository
	 * @return \Kdyby\Doctrine\QueryBuilder
	 */
	protected function doCreateCountQuery(Queryable $repository)
	{
		return $this->createBasicDql($repository)->select('COUNT(m.id)');
	}



	/**
	 * @param Queryable $repository
	 * @return \Kdyby\Doctrine\QueryBuilder
	 */
	private function createBasicDql(Queryable $repository)
	{
		$qb = $repository->createQueryBuilder()
			->select('m')->from(Monster::class, 'm');

		foreach ($this->filter as $modifier) {
			$modifier($qb);
		}

		return $qb;
	}

}
