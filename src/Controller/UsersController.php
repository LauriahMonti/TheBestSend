<?php

namespace App\Controller;

use App\Entity\Ad;
use App\Entity\User;
use App\Entity\UserFavorites;
use App\Form\AdModifyFormType;
use App\Repository\AdRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/users_", name="users")
 */
class UsersController extends Controller
{
    /**
     * @Route("/users", name="users")
     */
    public function index()
    {
        return $this->render('users/index.html.twig', [
            'controller_name' => 'UsersController',
        ]);
    }

    /**
     * @IsGranted("ROLE_USER")
     * @Route("/mesAnnonces/{id}", name="annoncesUsers",  methods={"GET"})
     */

    public function annoncesUser(EntityManagerInterface $entityManager): Response
    {

        $user = $this->getUser();
        $annonces = $entityManager
            ->getRepository(User::class)
            ->findAllJoinUser($user->getId());
        dump($annonces);

         return $this->render('users/annonceUser.html.twig', [
             'annonce'=>$annonces,
         ]);
    }
    /**
     * @Route("/logout", name="logout")
     */
    public function logout()
    {
        session_abort();

        return $this->redirectToRoute('app_logout');
    }
    /**
     * @Route("/{id}", name = "annonceDelete", methods={"DELETE"})
     */
    public function delete(Request $request, Ad $ad): Response
    {
        if ($this->isCsrfTokenValid('delete'.$ad->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($ad);
            $entityManager->flush();
        }
        $this->addFlash("success", "Annonce supprimé avec succes !");
        return $this->redirectToRoute('main');
    }
    /**
     * @Route("/ajoutFavoris", name="favorisAjout")
     */
    public function addFavoris(EntityManagerInterface $entityManager, Request $request, AdRepository $adRepository): Response
    {
        $favoris = new UserFavorites();
        $annonce = $adRepository->find($request->query->get('id'));

        $user = $this->getUser();

        $favoris->setCreated(new \DateTime('now'));
        $favoris->setUser($user);
        $favoris->setAnnonce($annonce);

        $entityManager->persist($favoris);
        $entityManager->flush();

        $this->addFlash("success", "Favoris ajouté !");
        return $this->redirectToRoute('main');

    }
    /**
     * @Route("/mesFavoris/{id}", name="favorisListe", methods={"GET"})
     */
    public function mesFavoris(EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        $favorite = $entityManager
            ->getRepository(UserFavorites::class)
            ->showFavorite($user->getId());
        dump($favorite);

        return $this->render('users/favorisUser.html.twig', [
            'favoris'=> $favorite,
        ]);
    }


}
