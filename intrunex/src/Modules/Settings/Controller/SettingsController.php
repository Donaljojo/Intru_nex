<?php

namespace App\Modules\Settings\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class SettingsController extends AbstractController
{
    #[Route('/settings', name: 'settings')]
    #[IsGranted('ROLE_USER')]
    public function index(): Response
    {
        return $this->render('settings/index.html.twig');
    }
}
