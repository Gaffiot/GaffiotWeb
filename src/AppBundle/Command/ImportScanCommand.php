<?php

namespace AppBundle\Command;

use AppBundle\Entity\Exam;
use AppBundle\Entity\Page;
use AppBundle\Entity\Subject;
use AppBundle\Entity\Type;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ImportScanCommand extends ContainerAwareCommand
{
    /**
     * @var EntityManager
     */
    private $em;


    protected function configure()
    {
        $this
            ->setName('import:scan')
            ->setDescription('Import from a scan.json file');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->em = $this->getContainer()->get("doctrine.orm.default_entity_manager");


        $output->writeln("##################################");
        $output->writeln("Starting import...");


        $pages = json_decode(file_get_contents('files/scan.json'), true);

        $progress = new ProgressBar($output, count($pages));

        // start and displays the progress bar
        $progress->start();
        $progress->setFormat("normal");
        $progress->setMessage("Importing all data...");


        for ($i = 0; $i < count($pages); $i++) {
            $id = $pages[$i]['id'];
            $word = $pages[$i]['word'];
            $file = $pages[$i]['file'];

            echo $word . " " . $file;

            $page = new Page();
            $page->setFirstWord($word);
            $page->setFile($file);
            $this->em->persist($page);
            $this->em->flush();

            $progress->advance();
        }
        $progress->finish();
        $output->writeln("Finished import.");
        $output->writeln("##################################");
    }
}