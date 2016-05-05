<?php

namespace Game\Map\Console;

use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Game\Console\BaseCommand;
use Teddy\Map\MapService;



/**
 * Example usage: php www/index.php teddy:generateMap -r 10 -s 2131231231 -e 4.8 -o 64,32,16,4
 */
class GenerateMapCommand extends BaseCommand
{

	/**
	 * @var MapService
	 * @inject
	 */
	public $mapService;



	protected function configure()
	{
		$this->setName('teddy:generateMap')
			->addOption('radius', 'r', InputOption::VALUE_REQUIRED, 'Radius (do not use too big! Use enlarge command)')
			->addOption('elevation', 'e', InputOption::VALUE_OPTIONAL, 'Elevation: float; bigger means map is farther')
			->addOption('seed', 's', InputOption::VALUE_OPTIONAL, 'Seed: int, 1 to 2e8; for random int generator')
			->addOption('octaves', 'o', InputOption::VALUE_OPTIONAL, 'Octaves: ints, separated by comas;')
			->setDescription('Generates map');
	}



	protected function execute(InputInterface $input, OutputInterface $output)
	{
		$radius = intVal($input->getOption('radius'));
		$seed = is_numeric($input->getOption('seed')) ? intVal($input->getOption('seed')) : NULL;
		$octaves = $input->getOption('octaves') ? explode(',', $input->getOption('octaves')) : NULL;
		if ($octaves) {
			$octaves = array_map(function ($element) {
				return intVal($element);
			}, $octaves);
		}

		$elevation = is_numeric($input->getOption('elevation')) ? (float) $input->getOption('elevation') : NULL;
		$map = $this->mapService->createMap($radius, $seed, $octaves, $elevation);
		$this->output->writeln('Map id: ' . $map->getId());

		return 0;
	}

}
