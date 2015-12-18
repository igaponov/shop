<?php

namespace Shop\InfrastructureBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class SiteMapCommand extends ContainerAwareCommand
{
    /**
     * @inheritDoc
     */
    protected function configure()
    {
        $this->setName('shop:site-map')
            ->addOption('host', 't', InputOption::VALUE_OPTIONAL, 'Host for urls', 'localhost')
            ->addOption('output', 'o', InputOption::VALUE_REQUIRED, 'Path to output file');
    }

    /**
     * @inheritDoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $context = $this->getContainer()->get('router')->getContext();
        $context->setHost($input->getOption('host'));
        $urlCollector = $this->getContainer()->get('shop.url_collector');
        $iterator = $urlCollector->collect();
        if ($file = $input->getOption('output')) {
            $file = new \SplFileObject($file, 'w');
            foreach ($iterator as $item) {
                $file->fwrite($item.PHP_EOL);
            }
            $file->fflush();
        } else {
            foreach ($iterator as $item) {
                $output->writeln($item);
            }
        }
    }
}