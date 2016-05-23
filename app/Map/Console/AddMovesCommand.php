<?php

namespace Game\Map\Console;

use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Game\Console\BaseCommand;
use Teddy\Map\MapService;



/**
 * Example usage: php www/index.php teddy:map:addMoves
 */
class AddMovesCommand extends BaseCommand
{

	protected function configure()
	{
		$this->setName('teddy:map:addMoves')
			->setDescription('Adds 10 moves');
	}



	protected function execute(InputInterface $input, OutputInterface $output)
	{
		$sql = 'UPDATE user SET moves = moves + 10';
		$this->entityManager->getConnection()->executeQuery($sql);
		return 0;
	}

}
