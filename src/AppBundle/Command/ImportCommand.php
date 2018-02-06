<?php

namespace AppBundle\Command;

use AppBundle\Entity\Word;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ImportCommand extends ContainerAwareCommand
{
    /**
     * @var EntityManager
     */
    private $em;


    protected function configure()
    {
        $this
            ->setName('import:json')
            ->setDescription('Import from a JSON file')
            ->addArgument(
                'from',
                InputArgument::REQUIRED,
                'start'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->em = $this->getContainer()->get("doctrine.orm.default_entity_manager");

        /*$query = $this->em->createQuery('DELETE AppBundle:Word');
        $query->execute();*/

        $start = $input->getArgument('from');
        $output->writeln("##################################");
        $output->writeln("Starting import...");


        $words = json_decode(file_get_contents('files/gaffiot.json'), true);

        $progress = new ProgressBar($output, count($words));
        // start and displays the progress bar
        $progress->start();
        $progress->setFormat("normal");
        $progress->setMessage("Importing all data...");


        for ($i = $start; $i < count($words); $i++) {
            $id = $words[$i]['id'];
            $latin_raw = $words[$i]['latin_raw'];
            $latin = $words[$i]['latin'];
            $french = $words[$i]['french'];
            echo $id . " " . $latin_raw . "\n";

            $french = preg_replace("/\\\\begintriplecolumns/", "", $french);
            $french = preg_replace("/\\\\vfill/", "", $french);
            $french = preg_replace("/\\\\eject/", "", $french);
            $french = preg_replace("/\\\\kkz\\{([^\\}]+)\\}/", "", $french);
            $french = preg_replace("/\\\\raise-0.3ex\\\\hbox\\{\\\\arabe([^\\}]+)\\}/", "$1", $french);
            $french = preg_replace("/\\\\aut\\{([^\\}]+)\\}/", "<aut>$1</aut>", $french);
            $french = preg_replace("/\\\\autp\\{([^\\}]+)\\}/", "<aut>$1</aut>", $french);
            $french = preg_replace("/\\\\cl\\{([^\\}]+)\\}/", "<i>$1</i>", $french);
            $french = preg_replace("/\\\\comm\\{([^\\}]+)\\}/", "", $french);
            $french = preg_replace("/\\\\des\\{([^\\}]+)\\}/", "<i>$1</i>", $french);
            $french = preg_replace("/\\\\el\\{([^\\}]+)\\}/", "<i>$1</i>", $french);
            $french = preg_replace("/\\\\es\\{([^\\}]+)\\}/", "<b>$1</b>", $french);
            $french = preg_replace("/\\\\etymgr\\{([^\\}]+)\\}/", "$1", $french);
            $french = preg_replace("/\\\\etyml\\{([^\\}]+)\\}/", "$1", $french);
            $french = preg_replace("/\\\\F/", "&#8611;", $french);
            $french = preg_replace("/\\\\freq\\{([^\\}]+)\\}/", "<i>$1</i>", $french);
            $french = preg_replace("/\\\\gen\\{([^\\}]+)\\}/", "$1", $french);
            $french = preg_replace("/\\\\gras\\{([^\\}]+)\\}/", "<strong>$1</strong>", $french);
            $french = preg_replace("/\\\\grec\\{([^\\}]+)\\}/", "$1", $french);
            $french = preg_replace("/\\\\ital\\{([^\\}]+)\\}/", "<i>$1</i>", $french);
            $french = preg_replace("/\\\\italp\\{([^\\}]+)\\}/", "<i>$1</i>", $french);
            $french = preg_replace("/\\\\lat\\{([^\\}]+)\\}/", "<i>$1</i>", $french);
            $french = preg_replace("/\\\\latc\\{([^\\}]+)\\}/", "<i>$1</i>", $french);
            $french = preg_replace("/\\\\latdim\\{([^\\}]+)\\}/", "<i>$1</i>", $french);
            $french = preg_replace("/\\\\latgen\\{([^\\}]+)\\}/", "<i>$1</i>", $french);
            $french = preg_replace("/\\\\latp\\{([^\\}]+)\\}/", "<i>$1</i>", $french);
            $french = preg_replace("/\\\\latpf\\{([^\\}]+)\\}/", "<i>$1</i>", $french);
            $french = preg_replace("/\\\\latpl\\{([^\\}]+)\\}/", "<i>$1</i>", $french);
            $french = preg_replace("/\\\\latv\\{([^\\}]+)\\}/", "<i>$1</i>", $french);
            $french = preg_replace("/\\\\oeuv\\{([^\\}]+)\\}/", "<i>$1</i>", $french);
            $french = preg_replace("/\\\\par/", "<br><br>", $french);
            $french = preg_replace("/\\\\pc\\{([^\\}]+)\\}/", "<aut>$1</aut>", $french);
            $french = preg_replace("/\\\\pca\\{([^\\}]+)\\}/", "<aut>$1</aut>", $french);
            $french = preg_replace("/\\\\pp\\{([^\\}]+)\\}/", "<br><br><pp>&para; $1</pp>", $french);
            $french = preg_replace("/\\\\qq\\{([^\\}]+)\\}/", "<br><br><qq>$1</qq>", $french);
            $french = preg_replace("/\\\\qqng\\{([^\\}]+)\\}/", "<br><br><qqng>$1</qqng>", $french);
            $french = preg_replace("/\\\\qq\\{([^\\}]+)\\}/", "<br><br><qq>$1</qq>", $french);
            $french = preg_replace("/\\\\refch\\{([^\\}]+)\\}/", "<i>$1</i>", $french);
            $french = preg_replace("/\\\\refchp\\{([^\\}]+)\\}/", "<i>$1</i>", $french);
            $french = preg_replace("/\\\\refgaf\\{([^\\}]+)\\}/", "<i>$1</i>", $french);
            $french = preg_replace("/\\\\romain\\{([^\\}]+)\\}/", "$i", $french);
            $french = preg_replace("/\\\\rub\\{([^\\}]+)\\}/", "<rub>$1</rub>", $french);
            $french = preg_replace("/\\\\S/", "&sect;", $french);
            $french = preg_replace("/\\|\\|/", "|| <br>", $french);
            $french = preg_replace("/\\\\up\\{([^\\}]+)\\}/", "<sup>$1</sup>", $french);
            $french = preg_replace("/\\\\desv\\{([^\\}]+)\\}/", "<i>$1</i>", $french);
            $french = preg_replace("/\\\\autz\\{([^\\}]+)\\}/", "<aut>$1</aut>", $french);


            $word = new Word();
            $word->setId($id);
            $word->setLatinRaw($latin_raw);
            $word->setLatin($latin);
            $word->setFrench($french);
            $this->em->persist($word);
            $this->em->flush();

            $progress->advance();
        }
        $progress->finish();
        $output->writeln("Finished import.");
        $output->writeln("##################################");
    }
}