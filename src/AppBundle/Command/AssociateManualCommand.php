<?php

namespace AppBundle\Command;

use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class AssociateManualCommand extends ContainerAwareCommand
{
    /**
     * @var EntityManager
     */
    private $em;


    protected function configure()
    {
        $this
            ->setName('associate:word:page')
            ->setDescription('Add a scan to a word')
            ->addArgument(
                'word',
                InputArgument::REQUIRED,
                'Start int'
            )
            ->addArgument(
                'page',
                InputArgument::REQUIRED,
                'Start int'
            );

    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->em = $this->getContainer()->get("doctrine.orm.default_entity_manager");


        $wordId = $input->getArgument('word');
        $pageId = $input->getArgument('page');

        $word = $this->em->getRepository('AppBundle:Word')->find($wordId);
        $page = $this->em->getRepository('AppBundle:Page')->find($pageId);


        $word->updatePages([$page]);
        $this->em->persist($page);
        $this->em->flush();
        echo "done";
    }
}