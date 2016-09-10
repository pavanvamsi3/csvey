<?php

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException;
use Symfony\Component\HttpFoundation\Request;

/**
 * Import Initial HealthTips Command
 *
 */
class HealthtipsCommand extends ContainerAwareCommand
{
    const HEALTH_TIPS = 'src/AppBundle/Data/healthtips.csv';

    /**
     * Initialize Services
     *
     * @param InputInterface  $input  - Input Interface
     * @param OutputInterface $output - Output Interface
     *
     * @return null
     */
    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        parent::initialize($input, $output);
        $this->container = $this->getContainer();
        $this->doctrine = $this->container->get('doctrine');
        $this->entityManager = $this->doctrine->getManager();
    }

    /**
     * Configure
     *
     * @return null
     */
    protected function configure()
    {
        $this->setName('import_health_tips')
            ->setDescription('Imports health tips');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $healthTipsFile = self::HEALTH_TIPS;
        if (!file_exists($healthTipsFile)) {
            $output->writeln("<error>Error: File not found<error>");

            return;
        }
        $healthTipsManager = $this->container->get('csvey_api.health_tip_manager');
        $handle = @fopen($healthTipsFile, "r");
        $healthTips = array();
        if ($handle) {
            while (($data = fgetcsv($handle)) != false) {
                $healthTips[] = $data;
            }
        } else {
            $output->writeln("<error>Error: Unable to Open File <error>");

            return;
        }
        fclose($handle);
        $keys = array('tip', 'age_range');
        foreach ($healthTips as $healthTip) {
            $postData = array_combine($keys, $healthTip);
            $healthTipsManager->add(json_encode($postData));
        }
    }
}
