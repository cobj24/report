<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LuckyControllerTwig extends AbstractController
{
    #[Route('/lucky', name: 'lucky_number')]
    public function number(): Response
    {
        $number = random_int(0, 100);

        $data = [
            'number' => $number,
        ];

        return $this->render('lucky_number.html.twig', $data);
    }

    #[Route('/', name: 'me')]
    public function me(): Response
    {
        return $this->render('me.html.twig');
    }

    #[Route('/about', name: 'about')]
    public function about(): Response
    {
        return $this->render('about.html.twig');
    }

    #[Route('/report', name: 'report')]
    public function report(): Response
    {
        return $this->render('report.html.twig');
    }

    #[Route('/library', name: 'library')]
    public function library(): Response
    {
        return $this->render('library.html.twig');
    }

    #[Route('/metrics', name: 'metrics')]
    public function metrics(): Response
    {
        return $this->render('metrics.html.twig');
    }
}
