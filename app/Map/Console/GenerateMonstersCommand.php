<?php

namespace Game\Map\Console;

use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Game\Console\BaseCommand;
use Game\Map\MapService;



/**
 * Example usage: php www/index.php teddy:generateMonsters -c 10 -l 1 -m 1
 */
class GenerateMonstersCommand extends BaseCommand
{

	/**
	 * @var MapService
	 * @inject
	 */
	public $mapService;



	protected function configure()
	{
		$this->setName('teddy:generateMonsters')
			->addOption('count', 'c', InputOption::VALUE_REQUIRED, 'Monsters to generate')
			->addOption('level', 'l', InputOption::VALUE_REQUIRED, 'Levevl of monsters')
			->addOption('map', 'm', InputOption::VALUE_REQUIRED, 'Map id')
			->setDescription('Generates monsters on Map');
	}



	protected function execute(InputInterface $input, OutputInterface $output)
	{
		$mapId = intVal($input->getOption('map'));
		$monsters = intVal($input->getOption('count'));
		$level = intVal($input->getOption('level'));

		$map = $this->mapService->getMap($mapId);
		if (!$map) {
			$this->output->writeln('Map not found');
			return 0;
		}

		$this->mapService->generateMonsters($map, $monsters, $level);
		return 0;
	}

}
