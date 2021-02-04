<?php

namespace App\Controller;

use App\Entity\Wish;
use App\Form\WishType;
use App\Repository\WishRepository;
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
        return $this->render('wish/index.html.twig', [
            'wishes' => $wishRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="wish_new", methods={"GET","POST"})
     * @param Request $request
     * @return Response
     */
    public function new(Request $request): Response
    {
        $wish = new Wish();
        $form = $this->createForm(WishType::class, $wish);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
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

    /**
     * @Route("/{id}", name="wish_show", methods={"GET"})
     * @param Wish $wish
     * @return Response
     */
    public function show(Wish $wish): Response
    {
        return $this->render('wish/show.html.twig', [
            'wish' => $wish,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="wish_edit", methods={"GET","POST"})
     * @param Request $request
     * @param Wish $wish
     * @return Response
     */
    public function edit(Request $request, Wish $wish): Response
    {
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
        if ($this->isCsrfTokenValid('delete'.$wish->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($wish);
            $entityManager->flush();
            $this->addFlash('danger', 'Le souhait a bien été supprimée.');
        }

        return $this->redirectToRoute('wish_index');
    }
}
