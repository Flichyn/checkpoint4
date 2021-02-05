<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Wish;
use App\Form\WishType;
use App\Repository\WishlistRepository;
use App\Repository\WishRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/wish")
 */
class WishController extends AbstractController
{
    /**
     * @Route("/", name="wish_index", methods={"GET"})
     * @param WishRepository $wishRepository
     * @return Response
     */
    public function index(WishRepository $wishRepository): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        return $this->render('wish/index.html.twig', [
            'wishes' => $wishRepository->findBy(['user' => $user]),
        ]);
    }

    /**
     * @Route("/new", name="wish_new", methods={"GET","POST"})
     * @param Request $request
     * @return Response
     */
    public function new(Request $request): Response
    {
        /** @var User $user */
        $this->getUser();

        $wish = new Wish();
        $form = $this->createForm(WishType::class, $wish);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $wish->setUser($user);
            $entityManager->persist($wish);
            $entityManager->flush();
            $this->addFlash('success', 'Le souhait a bien été ajouté.');

            return $this->redirectToRoute('wish_index');
        }

        return $this->render('wish/new.html.twig', [
            'wish' => $wish,
            'form' => $form->createView(),
        ]);
    }

//    /**
//     * @Route("/{id}", name="wish_show", methods={"GET"})
//     * @param Wish $wish
//     * @return Response
//     */
//    public function show(Wish $wish): Response
//    {
//        /** @var User $user */
//        $user = $this->getUser();
//
//        return $this->render('wish/show.html.twig', [
//            'wish' => $wish,
//        ]);
//    }

    /**
     * @Route("/{id}/edit", name="wish_edit", methods={"GET","POST"})
     * @param Wish $wish
     * @param Request $request
     * @param WishRepository $wishRepository
     * @return Response
     */
    public function edit(Wish $wish, Request $request, WishlistRepository $wishlistRepository, WishRepository $wishRepository): Response
    {
        /** @var User $user */
        $user = $this->getUser();

//        $wishlistrepo = $wishRepository->findBy(['user' => $user]);
//        $currentWish = $wishRepository->findCurrentWishId($wish);
//        $wishes = $wishRepository->selectAllWishesIdForUser($user->getId());
//        dump($currentWish);
//        dump($wishes);
//        dump($wishlistrepo);
//        dump($wish);
//        $entityManager = $this->getDoctrine()->getManager();
//        $query = $entityManager->cre
//        if (in_array($wish->getId(), $wishes)) {
//            $this->addFlash('danger', 'Vous n\'avez pas accès à ce souhait.');
//            return $this->redirectToRoute('wish_index');
//        }

        $form = $this->createForm(WishType::class, $wish);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'Le souhait a bien été modifié.');

            return $this->redirectToRoute('wish_index');
        }

        return $this->render('wish/edit.html.twig', [
            'wish' => $wish,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="wish_delete", methods={"DELETE"})
     * @param Request $request
     * @param Wish $wish
     * @return Response
     */
    public function delete(Request $request, Wish $wish): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        if ($this->isCsrfTokenValid('delete'.$wish->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($wish);
            $entityManager->flush();
            $this->addFlash('danger', 'Le souhait a bien été supprimée.');
        }

        return $this->redirectToRoute('wish_index');
    }
}
