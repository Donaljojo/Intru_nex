<?php

namespace App\Modules\LandingAndInfo\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class InfoController extends AbstractController
{
    #[Route('/about', name: 'about_page')]
    public function about(): Response
    {
        return $this->render('landing_and_info/info/about.html.twig');
    }

    #[Route('/contact', name: 'contact_page')]
    public function contact(): Response
    {
        return $this->render('landing_and_info/info/contact.html.twig');
    }

    #[Route('/documentation', name: 'documentation_page')]
    public function documentation(): Response
    {
        return $this->render('landing_and_info/info/documentation.html.twig');
    }
}
