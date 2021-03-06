<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Word;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\BrowserKit\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="home")
     * @Template()
     */
    public function indexAction()
    {
        $words = $this->getDoctrine()->getManager()
            ->getRepository(Word::class)
            ->createQueryBuilder('a')
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(100)
            ->getQuery()->getResult();

        return [
            'words' => $words,
            'current' => 1,
            'start' => 1,
            'end' => 3
        ];
    }

    /**
     * @Route("/p/{number}", name="page")
     * @Template()
     */
    public function pageAction($number)
    {
        $number = $number - 1;
        $words = $this->getDoctrine()->getManager()
            ->getRepository(Word::class)
            ->createQueryBuilder('a')
            ->orderBy('a.id', 'ASC')
            ->setFirstResult($number * 100)
            ->setMaxResults(100)
            ->getQuery()->getResult();

        if ($number == 0) {
            $start = 1;
        } else {
            $start = $number;
        }

        if ($number >= 720) {
            $start = 720;
            $end = 722;
        } else {
            $end = $number + 2;
        }
        $current = $number + 1;

        if ($current < 0 || $current > 722) {
            throw new NotFoundHttpException();
        }
        return [
            'words' => $words,
            'current' => $current,
            'start' => $start,
            'end' => $end
        ];
    }

    /**
     *
     * @Route("/search/{query}", name="search")
     * @return Response
     */
    public function searchAction($query)
    {
        $em = $this->getDoctrine()->getManager();

        $word = $em
            ->getRepository(Word::class);

        $queryWord = $word->createQueryBuilder('a')
            ->where('a.latin_raw LIKE :query')
            ->orwhere('a.latin_raw LIKE :query1')
            ->orwhere('a.latin_raw LIKE :query2')
            ->orwhere('a.latin_raw LIKE :query3')
            ->orwhere('a.latin_raw LIKE :query4')
            ->orwhere('a.latin_raw LIKE :query5')
            ->orderBy('a.id', 'ASC')
            ->setParameter('query', "" . $query . "%")
            ->setParameter('query1', "1 " . $query . "%")
            ->setParameter('query2', "2 " . $query . "%")
            ->setParameter('query3', "3 " . $query . "%")
            ->setParameter('query4', "4 " . $query . "%")
            ->setParameter('query5', "5 " . $query . "%");
        $words = $queryWord->getQuery()->getResult();

        $arr = [];
        foreach ($words as $key => $value) {
            $arr[] = ["id" => $value->getId(), "raw" => $value->getLatinRaw(), "latin" => $value->getLatin()];
        }


        return JsonResponse::fromJsonString(json_encode($arr));

    }


    /**
     * @Route("/license", name="license")
     * @Template()
     */
    public function licenseAction()
    {
        return [

        ];
    }

    /**
     * @Route("/{id}", name="detail")
     * @Template()
     */
    public function detailAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $word = $em->getRepository('AppBundle:Word')->find($id);

        return [
            'word' => $word,
            'previous' => $this->getPreviousWord($id),
            'next' => $this->getNextWord($id)
        ];
    }

    public function getPreviousWord($id)
    {
        return $this->getDoctrine()->getManager()->getRepository(Word::class)
            ->createQueryBuilder('a')
            ->select('a')
            ->where('a.id < :id')
            ->setParameter(':id', $id)
            ->orderBy('a.id', 'DESC')
            ->setFirstResult(0)
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function getNextWord($id)
    {
        return $this->getDoctrine()->getManager()->getRepository(Word::class)
            ->createQueryBuilder('a')
            ->select('a')
            ->where('a.id > :id')
            ->setParameter(':id', $id)
            ->orderBy('a.id', 'ASC')
            ->setFirstResult(0)
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
