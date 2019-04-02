<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends Controller
{
    /**
     * @Route("/", name="main")
     */
    public function index()
    {
        return $this->render('main/index.html.twig', [
            'controller_name' => 'MainController',
        ]);
    }
    /**
     * @Route("/faq")
     */
    public function faq()
    {
        return $this->render('main/faq.html.twig');
    }
    /**
     * @Route("/cgu")
     */
    public function cgu()
    {
        return $this->render('main/cgu.html.twig');
    }
}
