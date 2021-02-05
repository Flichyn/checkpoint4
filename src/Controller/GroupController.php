<?php

namespace App\Controller;

use App\Entity\Group;
use App\Entity\User;
use App\Form\GroupType;
use App\Repository\GroupRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/group")
 */
class GroupController extends AbstractController
{
    /**
     * @Route("/", name="group_index", methods={"GET"})
     * @param GroupRepository $groupRepository
     * @return Response
     */
    public function index(GroupRepository $groupRepository): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        $groups = $user->getGroups();

        return $this->render('group/index.html.twig', [
            'groups' => $groups,
        ]);
    }

    /**
     * @Route("/new", name="group_new", methods={"GET","POST"})
     * @param Request $request
     * @return Response
     */
    public function new(Request $request): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        $group = new Group();
        $form = $this->createForm(GroupType::class, $group, ['user' => $user->getId()]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($group);
            $entityManager->flush();
            $this->addFlash('success', 'Le groupe a bien été crée.');

            return $this->redirectToRoute('group_index');
        }

        return $this->render('group/new.html.twig', [
            'group' => $group,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="group_show", methods={"GET"})
     * @param Group $group
     * @param UserRepository $userRepository
     * @param GroupRepository $groupRepository
     * @return Response
     */
    public function show(Group $group, UserRepository $userRepository, GroupRepository $groupRepository): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        $groups = $user->getGroups()->getValues();
//        $members = $groupRepository->selectMembersOfMyGroup($group);
//        dump($members);

        if (!in_array($group, $groups)) {
            $this->addFlash('danger', 'Vous n\'avez pas accès à ce groupe.');
            return $this->redirectToRoute('group_index');
        }

        $users = $userRepository->findBy(['id' => $group]);
        return $this->render('group/show.html.twig', [
            'group' => $group,
            'users' => $users
        ]);
    }

    /**
     * @Route("/{id}/edit", name="group_edit", methods={"GET","POST"})
     * @param Request $request
     * @param Group $group
     * @return Response
     */
    public function edit(Request $request, Group $group): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        $groups = $user->getGroups()->getValues();
        if (!in_array($group, $groups)) {
            $this->addFlash('danger', 'Vous n\'avez pas accès à ce groupe.');
            return $this->redirectToRoute('group_index');
        }

        $form = $this->createForm(GroupType::class, $group, ['user' => $user->getId()]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'Le groupe a bien été modifié.');

            return $this->redirectToRoute('group_index');
        }

        return $this->render('group/edit.html.twig', [
            'group' => $group,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="group_delete", methods={"DELETE"})
     * @param Request $request
     * @param Group $group
     * @return Response
     */
    public function delete(Request $request, Group $group): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        $groups = $user->getGroups()->getValues();
        if (!in_array($group, $groups)) {
            $this->addFlash('danger', 'Vous n\'avez pas accès à ce groupe.');
            return $this->redirectToRoute('group_index');
        }

        if ($this->isCsrfTokenValid('delete'.$group->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($group);
            $entityManager->flush();
        }

        return $this->redirectToRoute('group_index');
    }
}
