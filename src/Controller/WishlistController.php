<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Wishlist;
use App\Form\WishlistType;
use App\Repository\WishlistRepository;
use App\Repository\WishRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/wishlist")
 */
class WishlistController extends AbstractController
{
    /**
     * @Route("/", name="wishlist_index", methods={"GET"})
     * @param WishlistRepository $wishlistRepository
     * @return Response
     */
    public function index(WishlistRepository $wishlistRepository): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        return $this->render('wishlist/index.html.twig', [
            'wishlists' => $user->getWishlists()
        ]);
    }

    /**
     * @Route("/new", name="wishlist_new", methods={"GET","POST"})
     * @param Request $request
     * @return Response
     */
    public function new(Request $request): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        $wishlist = new Wishlist();
        $form = $this->createForm(WishlistType::class, $wishlist, ['user' => $user->getId()]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $wishlist->setUser($user);
            $entityManager->persist($wishlist);
            $entityManager->flush();
            $this->addFlash('success', 'La liste de souhaits a bien été ajoutée.');


            return $this->redirectToRoute('wishlist_index');
        }

        return $this->render('wishlist/new.html.twig', [
            'wishlist' => $wishlist,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="wishlist_show", methods={"GET"})
     * @param Wishlist $wishlist
     * @param WishRepository $wishRepository
     * @return Response
     */
    public function show(Wishlist $wishlist, WishRepository $wishRepository): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        $wishlists = $user->getWishlists()->getValues();
        if (!in_array($wishlist, $wishlists)) {
            $this->addFlash('danger', 'Vous n\'avez pas accès à cette liste.');
            return $this->redirectToRoute('wishlist_index');
        }

        $wishes = $wishRepository->findOneBy(['id' => $wishlist]);
        return $this->render('wishlist/show.html.twig', [
            'wishlist' => $wishlist,
            'wishes' => $wishes,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="wishlist_edit", methods={"GET","POST"})
     * @param Request $request
     * @param Wishlist $wishlist
     * @return Response
     */
    public function edit(Request $request, Wishlist $wishlist): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        $wishlists = $user->getWishlists()->getValues();
        if (!in_array($wishlist, $wishlists)) {
            $this->addFlash('danger', 'Vous n\'avez pas accès à cette liste.');
            return $this->redirectToRoute('wishlist_index');
        }

        $form = $this->createForm(WishlistType::class, $wishlist, ['user' => $user->getId()]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'La liste de souhaits a bien été modifiée.');


            return $this->redirectToRoute('wishlist_index');
        }

        return $this->render('wishlist/edit.html.twig', [
            'wishlist' => $wishlist,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="wishlist_delete", methods={"DELETE"})
     * @param Request $request
     * @param Wishlist $wishlist
     * @return Response
     */
    public function delete(Request $request, Wishlist $wishlist): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        $wishlists = $user->getWishlists()->getValues();
        $group = $wishlist->getGroups();
        if (!in_array($wishlist, $wishlists)) {
            $this->addFlash('danger', 'Vous n\'avez pas accès à cette liste.');
            return $this->redirectToRoute('wishlist_index');
        }

        if ($this->isCsrfTokenValid('delete'.$wishlist->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($wishlist);
            $entityManager->flush();
            $this->addFlash('danger', 'La liste de souhaits a bien été supprimée.');
        }

        return $this->redirectToRoute('wishlist_index');
    }
}
