<?php


namespace AppBundle\Command;

use AppBundle\Entity\Exam;
use AppBundle\Entity\Subject;
use AppBundle\Entity\Type;
use AppBundle\Entity\Word;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class AssociatePageWordCommand extends ContainerAwareCommand
{
    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @var Word
     */
    private $currentWord;

    /**
     * @var Word
     */
    private $lastWord;


    protected function configure()
    {
        $this
            ->setName('associate:pages')
            ->setDescription('Add a scan to a word')
            ->addArgument(
                'id',
                InputArgument::REQUIRED,
                'Start int'
            )
            ->addArgument(
                'count',
                InputArgument::REQUIRED,
                'Start int'
            )
            ->addArgument(
                'scan',
                InputArgument::REQUIRED,
                'Start int'
            );

    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->em = $this->getContainer()->get("doctrine.orm.default_entity_manager");


        $id = $input->getArgument('id');
        $count = $input->getArgument('count');
        $scanStart = $input->getArgument('scan');

        $output->writeln("##################################");
        $output->writeln("Starting import...");


        $words = $this->em
            ->getRepository(Word::class)
            ->createQueryBuilder('a')
            ->where('a.id >= :id')
            ->orderBy('a.id', 'ASC')
            ->setParameter('id', $id)
            ->setMaxResults($count)
            ->getQuery()->getResult();

        $pages = $this->em->getRepository('AppBundle:Page')->findAll();


        $progress = new ProgressBar($output, count($words));
        // start and displays the progress bar
        $progress->start();
        $progress->setFormat("normal");
        $progress->setMessage("Associating all data...");

        $lastComparison = 0;
        $j = $scanStart;
        for ($i = 0; $i < count($words); $i++) {
            $this->lastWord = $this->currentWord;
            $this->currentWord = $words[$i];

            $latin = strtolower($this->currentWord->getLatinRaw());

            if (substr($latin, 0, 2) == "1 " ||
                substr($latin, 0, 2) == "2 " ||
                substr($latin, 0, 2) == "3 " ||
                substr($latin, 0, 2) == "4 " ||
                substr($latin, 0, 2) == "5 ") {
                $latin = substr($latin, 2);
            }
            $page = $pages[$j];
            $next = $pages[$j + 1];
            $comp = strnatcmp($latin, $next);

            echo $comp;

            if (strnatcmp($latin, $next) == 0) {
                echo "-count up-";
                $page = $next;
                $j = $j + 1;
            }

            echo "-j:" . $j . "-";

            $this->currentWord->setPages([$page]);
            $this->em->persist($this->currentWord);
            $this->em->flush();

            if ($lastComparison == -1 && $comp == 1) {
                echo "2 pages...and count up";
                $page = $next;

                $this->lastWord->updatePages([$page]);
                $this->em->persist($this->currentWord);
                $this->em->flush();

                $j = $j + 1;
            }

            $lastComparison = $comp;
            echo " " . $this->currentWord->getId() . "-" . $latin . " " . $page . "\n";
            $progress->advance();
        }
        $progress->finish();
        $output->writeln("Finished associating.");
        $output->writeln("##################################");
    }
}