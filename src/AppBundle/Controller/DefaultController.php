<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

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
