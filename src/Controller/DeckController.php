<?php

namespace App\Controller;

use App\Card\DeckOfCards;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class DeckController extends AbstractController
{
    #[Route('/api/deck', name: 'api_deck', methods: ['GET'])]
    public function deck(SessionInterface $session): JsonResponse
    {
        $deck = $session->get('deck', new DeckOfCards(true));
        return $this->json($deck->getCards());
    }

    #[Route('/api/deck/shuffle', name: 'api_shuffle', methods: ['POST'])]
    public function shuffle(SessionInterface $session): JsonResponse
    {
        $deck = new DeckOfCards(true);
        $deck->shuffle();
        $session->set('deck', $deck);

        return $this->json($deck->getCards());
    }

    #[Route('/api/deck/draw', name: 'api_draw_one', methods: ['POST'])]
    public function drawOne(SessionInterface $session): JsonResponse
    {
        /** @var DeckOfCards $deck */
        $deck = $session->get('deck', new DeckOfCards(true));
        $drawn = $deck->draw();
        $session->set('deck', $deck);

        return $this->json([
            'drawn' => $drawn,
            'remaining' => $deck->count()
        ]);
    }

    #[Route('/api/deck/draw/{number<\d+>}', name: 'api_draw_number', methods: ['POST'])]
    public function drawNumber(SessionInterface $session, int $number): JsonResponse
    {
        /** @var DeckOfCards $deck */
        $deck = $session->get('deck', new DeckOfCards(true));
        $drawn = $deck->draw($number);
        $session->set('deck', $deck);

        return $this->json([
            'drawn' => $drawn,
            'remaining' => $deck->count()
        ]);
    }
}
