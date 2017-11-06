<?php


namespace AppBundle\Command;

use AppBundle\Entity\Exam;
use AppBundle\Entity\Image;
use AppBundle\Entity\Subject;
use AppBundle\Entity\Type;
use AppBundle\Entity\Word;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ImportImagesCommand extends ContainerAwareCommand
{
    /**
     * @var EntityManager
     */
    private $em;


    protected function configure()
    {
        $this
            ->setName('import:images')
            ->setDescription('Import from a JSON file');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->em = $this->getContainer()->get("doctrine.orm.default_entity_manager");

        $query = $this->em->createQuery('DELETE AppBundle:Image');
        $query->execute();

        $output->writeln("##################################");
        $output->writeln("Starting import...");


        $words = json_decode(file_get_contents('files/pictures.json'), true);

        $progress = new ProgressBar($output, count($words));
        // start and displays the progress bar
        $progress->start();
        $progress->setFormat("normal");
        $progress->setMessage("Importing all data...");


        for ($i = 0; $i < count($words); $i++) {
            $id = $words[$i]['id'];
            $wordRaw = $words[$i]['word'];
            $paragraph = $words[$i]['paragraph'];
            $file = $words[$i]['image'];


            $word = $this->em
                ->getRepository(Word::class);

            $queryWord = $word->createQueryBuilder('a')
                ->where('a.latin_raw LIKE :query')
                ->orwhere('a.latin_raw LIKE :query1')
                ->orwhere('a.latin_raw LIKE :query2')
                ->orwhere('a.latin_raw LIKE :query3')
                ->orwhere('a.latin_raw LIKE :query4')
                ->orwhere('a.latin_raw LIKE :query5')
                ->orderBy('a.id', 'ASC')
                ->setParameter('query', "" . $wordRaw . "%")
                ->setParameter('query1', "1 " . $wordRaw . "%")
                ->setParameter('query2', "2 " . $wordRaw . "%")
                ->setParameter('query3', "3 " . $wordRaw . "%")
                ->setParameter('query4', "4 " . $wordRaw . "%")
                ->setParameter('query5', "5 " . $wordRaw . "%");
            $word = $queryWord->getQuery()->getResult();
    var_dump($words);

            echo $id . " " . $word[0]->getLatin() . "\n";
/*
            $image = new Image();
            $image->setWord($word);
            $image->setParagraph($paragraph);
            $image->setFile($file);
            $this->em->persist($image);
            $this->em->flush();*/

            $progress->advance();
        }
        $progress->finish();
        $output->writeln("Finished import.");
        $output->writeln("##################################");
    }
}