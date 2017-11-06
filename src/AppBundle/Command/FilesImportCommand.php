<?php


namespace AppBundle\Command;

use AppBundle\Entity\Exam;
use AppBundle\Entity\File;
use AppBundle\Entity\Subject;
use AppBundle\Entity\Type;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class FilesImportCommand extends ContainerAwareCommand
{
    /**
     * @var EntityManager
     */
    private $em;


    protected function configure()
    {
        $this
            ->setName('import:files')
            ->setDescription('Import from a JSON file');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->em = $this->getContainer()->get("doctrine.orm.default_entity_manager");

        /*$query = $this->em->createQuery('DELETE AppBundle:Word');
        $query->execute();*/

        $output->writeln("##################################");
        $output->writeln("Starting import...");


        $words = json_decode(file_get_contents('files/scan.json'), true);

        $progress = new ProgressBar($output, count($words));
        // start and displays the progress bar
        $progress->start();
        $progress->setFormat("normal");
        $progress->setMessage("Importing all data...");


        for ($i = 0; $i < count($words); $i++) {
            $id = $words[$i]['id'];
            $word = $words[$i]['word'];
            $file = $words[$i]['file'];
            echo $id . " " . $word . "\n";

            $fileImport = new File();
            $fileImport->setId($id);
            $fileImport->setWord($word);
            $fileImport->setFile($file);
            $this->em->persist($fileImport);
            $this->em->flush();

            $progress->advance();
        }
        $progress->finish();
        $output->writeln("Finished import.");
        $output->writeln("##################################");
    }
}