<?php

namespace App\Controller;

use App\Entity\Ad;
use App\Form\AdFormType;
use App\Repository\AdRepository;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/annonce_", name="annonce")
 */
class AnnonceController extends Controller
{
    /**
     * @IsGranted("ROLE_USER")
     * @Route("/ajouterAnnonce", name="creer")
     */
    public function ajouterAnnonce(EntityManagerInterface $entityManager, Request $request)
    {
        $annonce = new Ad();
        $form = $this->createForm(AdFormType::class, $annonce);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $annonce->setDateCreated(new \DateTime('now'));
            $annonce->setUser($this->getUser());
            $entityManager->persist($annonce);

            $entityManager->flush();


            $this->addFlash("success", "Annonce crée avec succes");


            return $this->redirectToRoute('main');
        }


        return $this->render('annonce/ajouterAnnonce.html.twig', ["form" => $form->createView()]);
    }

    /**
     * @Route("/rechercherAnnonce", name="recherche")
     */
    public function rechercheAnnonce(EntityManagerInterface $entityManager, CategoryRepository $category)
    {
        $annonces = $entityManager
            ->getRepository(Ad::class)
            ->findAllJoinCategory();
        if(!$annonces)
        {
            throw $this->createNotFoundException('Annonce non trouvé');
        }
        $categories = $category->findAll();
        if (!$categories)
        {
            throw $this->createNotFoundException('Catégorie non trouvé');
        }


        return $this->render('annonce/rechercheAnnonce.html.twig', [
            'annonces'=> $annonces,
            'category'=> $categories

        ]);
    }

    /**
     * @Route("/details/{id}", name = "details", requirements={"id"="\d+"})
     */
    public function detailsAnnonce(Ad $annonce)
    {
        if (!$annonce)
        {
            throw $this->createNotFoundException("Attention! Cet annonce n'existe pas!");
        }
        return $this->render('annonce/detailsAnnonce.html.twig', compact('annonce'));
    }

    /**
     * @Route("/filter/cp", name ="searchZip")
     */
    public function searchByCp(AdRepository $adR, Request $request)
    {
        $annonces = $adR
            ->search($request->get('search'));
        return $this->render('annonce/rechercheAnnonce.html.twig', compact('annonces'));
    }

    /**
     * @Route("/filter/cp", name="searchZip")
     */
    public function searchByCategory(AdRepository $adR, Request $request)
    {
        $annonces = $adR
            ->searchCategory($request->get('search'));

        return $this->render('annonce/rechercheAnnonce.html.twig', compact('annonces'));
    }
}