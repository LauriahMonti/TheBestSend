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
        dump($annonces);
        $categories = $category->findAll();

        return $this->render('annonce/rechercheAnnonce.html.twig', [
            'annonces'=> $annonces,
            'category'=> $categories

        ]);
    }

    /**
     * @Route("/details/{id}", name = "details", requirements={"id"="\d+"})
     */
    public function detailsAnnonce(Ad $annonce, EntityManagerInterface $entityManager)
    {
        return $this->render('annonce/detailsAnnonce.html.twig', compact('annonce'));
    }

    /**
     * @Route("/filter/cp", name ="searchZip")
     * @param AdRepository $adR
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     *
     */
    public function searchByCp(AdRepository $adR, Request $request)
    {

        $annonces = $adR
            ->search($request->get('zip'));


        return $this->render('annonce/rechercheAnnonce.html.twig', compact('annonces'));
    }

    /**
     * @Route("filter/category", name="viewCat")
     */
    public function vueByCategory(CategoryRepository $categorie, Request $request)
    {
        $annonces = $categorie->searchCategory($request->get('category'));
        return $this->render('annonce/rechercheAnnonce.html.twig', compact('annonces'));
    }
}