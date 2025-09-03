<?php

namespace App\Controller\Modules\Dashboard\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{
    #[Route('/modules/dashboard/controller/dashboard', name: 'app_modules_dashboard_controller_dashboard')]
    public function index(): Response
    {
        return $this->render('modules/dashboard/controller/dashboard/index.html.twig', [
            'controller_name' => 'DashboardController',
        ]);
    }
}
