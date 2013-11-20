<?php
namespace Webit\Bundle\PriceComparatorCeneoBundle\Command;


use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Webit\Bundle\PriceComparatorCeneoBundle\Synchronizer\GroupAttributeSynchronizerInterface;

class GroupAttributeSynchronizeCommand extends ContainerAwareCommand
{
    /**
     *
     * @var GroupAttributeSynchronizerInterface
     */
    private $synchronizer;

    protected function configure()
    {
        parent::configure();
        $this->setName('webit:ceneo:group-attribute-synchronize')
            ->setDescription('Synchronize Ceneo groups and attributes');
    }

    /**
     * (non-PHPdoc)
     * @see Symfony\Component\Console\Command.Command::initialize()
     */
    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        parent::initialize($input, $output);
        $this->synchronizer = $this->getContainer()->get('webit_price_comparator_ceneo.group_attribute_synchronizer');
        $this->synchronizer->setOutput($output);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
       $this->synchronizer->synchronizeGroupsAndAttributes();
    }
}