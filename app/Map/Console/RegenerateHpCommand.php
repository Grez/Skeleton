<?php

namespace Game\Map\Console;

use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Game\Console\BaseCommand;
use Teddy\Map\MapService;



/**
 * Example usage: php www/index.php teddy:map:regenerateHp
 */
class RegenerateHpCommand extends BaseCommand
{

	protected function configure()
	{
		$this->setName('teddy:map:regenerateHp')
			->setDescription('Regenerates 10*level hps');
	}



	protected function execute(InputInterface $input, OutputInterface $output)
	{
		$sql = 'UPDATE user SET current_hp = current_hp + 10 * level';
		$this->entityManager->getConnection()->executeQuery($sql);

		$sql = 'UPDATE user SET current_hp = IF(current_hp > 100 * level, 100 * level, current_hp)';
		$this->entityManager->getConnection()->executeQuery($sql);

		return 0;
	}

}
