<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Controller för projektrelaterade sidor.
 */
class ProjectController extends AbstractController
{
    /**
     * Visar startsidan för projektet.
     *
     * @return Response Renderar 'projekt/projekt.html.twig'.
     */
    #[Route('/proj', name: 'proj')]
    public function index(): Response
    {
        return $this->render('projekt/projekt.html.twig');
    }

    /**
     * Visar informationssidan "About" för projektet.
     *
     * @return Response Renderar 'projekt/about.html.twig'.
     */
    #[Route('/proj/about', name: 'proj_about')]
    public function about(): Response
    {
        return $this->render('projekt/about.html.twig');
    }
}
