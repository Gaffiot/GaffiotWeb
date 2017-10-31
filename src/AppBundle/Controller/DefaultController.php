<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Word;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\BrowserKit\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="home")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $words = $em->getRepository('AppBundle:Word')->findAll();

        return [
            'words' => $words
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
            ->orderBy('a.latin', 'ASC')
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
            'word' => $word
        ];
    }
}
