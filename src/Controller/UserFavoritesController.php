<?php

namespace App\Controller;

use App\Entity\Ad;
use App\Entity\UserFavorites;
use App\Form\UserFavoritesType;
use App\Repository\UserFavoritesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/user/favorites")
 */
class UserFavoritesController extends Controller
{
    /**
     * @Route("/", name="user_favorites_index", methods={"GET"})
     */
    public function index(UserFavoritesRepository $userFavoritesRepository): Response
    {
        return $this->render('user_favorites/index.html.twig', [
            'user_favorites' => $userFavoritesRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="user_favorites_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $userFavorite = new UserFavorites();
        $annonce = new Ad();
        $form = $this->createForm(UserFavoritesType::class, $userFavorite);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $userFavorite->setDateCreated(new \DateTime('now'));
            $userFavorite->setUser($this->getUser());
            $entityManager->persist($userFavorite);
            $entityManager->flush();

            return $this->redirectToRoute('user_favorites_index');
        }

        return $this->render('user_favorites/new.html.twig', [
            'user_favorite' => $userFavorite,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="user_favorites_show", methods={"GET"})
     */
    public function show(UserFavorites $userFavorite): Response
    {
        return $this->render('user_favorites/show.html.twig', [
            'user_favorite' => $userFavorite,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="user_favorites_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, UserFavorites $userFavorite): Response
    {
        $form = $this->createForm(UserFavoritesType::class, $userFavorite);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('user_favorites_index', [
                'id' => $userFavorite->getId(),
            ]);
        }

        return $this->render('user_favorites/edit.html.twig', [
            'user_favorite' => $userFavorite,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="user_favorites_delete", methods={"DELETE"})
     */
    public function delete(Request $request, UserFavorites $userFavorite): Response
    {
        if ($this->isCsrfTokenValid('delete'.$userFavorite->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($userFavorite);
            $entityManager->flush();
        }

        return $this->redirectToRoute('user_favorites_index');
    }
}
