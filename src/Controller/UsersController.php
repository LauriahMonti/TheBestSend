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
 * @Route("/users", name="users")
 */
class UsersController extends Controller
{

    /**
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

        return $this->render('users/favorisUser.html.twig', [
            'favoris'=> $favorite,
        ]);
    }
    /**
     * @Route("/{id}", name="favorisDelete", methods={"DELETE"})
     */
    public function delete(Request $request, UserFavorites $userFavorite): Response
    {
        if ($this->isCsrfTokenValid('delete'.$userFavorite->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($userFavorite);
            $entityManager->flush();
            $this->addFlash("success", "Favoris supprimé !");
        }

        return $this->redirectToRoute('main');
    }

    /**
     * @Route("/{id}", name="annonceDelete", methods={"DELETE"})
     * @param Request $request
     * @param Ad $ad
     * @return Response
     */
    public function deleteAnnonce(Request $request, Ad $ad): Response
    {
        if ($this->isCsrfTokenValid('delete'.$ad->getId(), $request->request->get('_token'))) {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($ad);
        $entityManager->flush();
    }

    return $this->redirectToRoute('main');
}

}
