<?php

namespace App\Controller;

use App\Repository\AdRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends Controller
{
    /**
     * @Route("/", name="main")
     */
    public function index(AdRepository $adRepository): Response
    {
        return $this->render('ad/index.html.twig', [
            'ads' => $adRepository->findAll(),
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
