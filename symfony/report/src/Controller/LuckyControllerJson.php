<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;


class LuckyControllerJson extends AbstractController
{
    #[Route('/api/lucky', name: 'api_lucky')]
    public function lucky(): JsonResponse
    {
        $number = random_int(1, 100);

        return new JsonResponse([
            'lucky_number' => $number,
            'date' => (new \DateTime())->format('Y-m-d'),
            'timestamp' => (new \DateTime())->format('H:i:s')
        ]);
    }

    #[Route('/api', name: 'api_landing')]
    public function apiLanding(): Response
    {
        return $this->render('api.html.twig');
    }

    #[Route('/api/quote', name: 'api_quote')]
    public function quote(): JsonResponse
    {
        $quotes = [
            "Code is poetry.",
            "Don't repeat yourself. Unless it’s coffee.",
            "Simplicity is the soul of efficiency.",
        ];

        $randomQuote = $quotes[array_rand($quotes)];
        $now = new \DateTime();

        return new JsonResponse([
            'quote' => $randomQuote,
            'date' => $now->format('Y-m-d'),
            'timestamp' => $now->format('H:i:s'),
        ]);
    }
}
