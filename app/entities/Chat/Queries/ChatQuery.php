<?php

namespace Game\Entities\Chat;

use Doctrine\ORM\QueryBuilder;
use Game\Entities\User\User;
use Kdyby\Persistence\Queryable;


class ChatQuery extends \Kdyby\Doctrine\QueryObject
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
	 * @param User $user
	 * @return $this
	 */
	public function onlyByUser(User $user)
	{
		$this->filter[] = function (QueryBuilder $qb) use ($user) {
			$qb->andWhere('m.id = :author', $user);
		};
		return $this;
	}



	/**
	 * @param string $order
	 * @return $this
	 */
	public function orderByPostedAt($order = 'DESC')
	{
		$this->select[] = function (QueryBuilder $qb) use ($order) {
			$qb->addOrderBy('m.postedAt', $order);
		};
		return $this;
	}



	/**
	 * @param Queryable $repository
	 * @return \Doctrine\ORM\Query|\Doctrine\ORM\QueryBuilder
	 */
	protected function doCreateQuery(Queryable $repository)
	{
		$qb = $this->createBasicDql($repository);

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
			->select('m')->from(\Game\Entities\Chat\ChatMessage::class, 'm')
			->innerJoin('m.author', 'u');

		foreach ($this->filter as $modifier) {
			$modifier($qb);
		}

		return $qb;
	}

}
