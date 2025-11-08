<?php

namespace App\Modules\Analytics\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class AnalyticsController extends AbstractController
{
    #[Route('/analytics', name: 'analytics')]
    #[IsGranted('ROLE_USER')]
    public function index(): Response
    {
        return $this->render('analytics/index.html.twig');
    }
}
