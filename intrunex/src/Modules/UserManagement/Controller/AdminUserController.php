<?php

namespace App\Modules\UserManagement\Controller;

use App\Entity\User;
use App\Form\UserRegistrationFormType; // reuse registration form for creation
use App\Form\UserRoleEditFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AdminUserController extends AbstractController
{
    #[Route('/admin/users', name: 'admin_user_list')]
    public function list(EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $users = $em->getRepository(User::class)->findAll();
        return $this->render('user_management/admin/user_list.html.twig', ['users' => $users]);
    }

    #[Route('/admin/users/create', name: 'admin_user_create')]
    public function create(Request $request, EntityManagerInterface $em, UserPasswordHasherInterface $passwordHasher): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $user = new User();
        $form = $this->createForm(UserRegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Set roles (or use roles from form if you allow)
            $user->setRoles(['ROLE_USER']);

            // Hash password (assuming plainPassword field used in form)
            $plainPassword = $form->get('plainPassword')->getData();
            $hashedPassword = $passwordHasher->hashPassword($user, $plainPassword);
            $user->setPassword($hashedPassword);

            $em->persist($user);
            $em->flush();

            $this->addFlash('success', 'User created successfully.');

            return $this->redirectToRoute('admin_user_list');
        }

        return $this->render('user_management/admin/user_create.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    #[Route('/admin/users/{id}/edit', name: 'admin_user_edit')]
    public function edit(User $user, Request $request, EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $form = $this->createForm(UserRoleEditFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success', 'User roles updated successfully.');

            return $this->redirectToRoute('admin_user_list');
        }

        return $this->render('user_management/admin/user_edit.html.twig', [
            'editForm' => $form->createView(),
            'user' => $user,
        ]);
    }

    #[Route('/admin/users/{id}/delete', name: 'admin_user_delete', methods: ['POST'])]
    public function delete(User $user, Request $request, EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        if ($this->isCsrfTokenValid('delete-user'.$user->getId(), $request->request->get('_token'))) {
            // Prevent admin from deleting themselves
            if ($user === $this->getUser()) {
                $this->addFlash('error', 'You cannot delete your own account.');
                return $this->redirectToRoute('admin_user_list');
            }

            $em->remove($user);
            $em->flush();

            $this->addFlash('success', 'User deleted successfully.');
        } else {
            $this->addFlash('error', 'Invalid CSRF token.');
        }

        return $this->redirectToRoute('admin_user_list');
    }
}





