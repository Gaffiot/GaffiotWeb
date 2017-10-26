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
            ->orderBy('a.latin', 'ASC')
            ->setParameter('query', "%" . $query . "%");
        $words = $queryWord->getQuery()->getResult();

        $arr = [];
        foreach ($words as $key => $value) {
            $arr[] = ["id" => $value->getId(), "raw" => $value->getLatinRaw(), "latin" => $value->getLatin()];
        }


        return JsonResponse::fromJsonString(json_encode($arr));

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
