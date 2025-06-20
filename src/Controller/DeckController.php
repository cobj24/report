<?php

namespace App\Controller;

use App\Card\DeckOfCards;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * API Controller for managing deck operations via JSON responses.
 */
class DeckController extends AbstractController
{
    /**
     * Get the current deck of cards from the session or create a new one.
     *
     * @param SessionInterface $session the session interface
     *
     * @return JsonResponse JSON response containing the list of cards
     */
    #[Route('/api/deck', name: 'api_deck', methods: ['GET'])]
    public function deck(SessionInterface $session): JsonResponse
    {
        $deck = $session->get('deck', new DeckOfCards(true));

        return $this->json($deck->getCards());
    }

    /**
     * Shuffle a new deck of cards, save it in session, and return it.
     *
     * @param SessionInterface $session the session interface
     *
     * @return JsonResponse JSON response with the shuffled cards
     */
    #[Route('/api/deck/shuffle', name: 'api_shuffle', methods: ['POST'])]
    public function shuffle(SessionInterface $session): JsonResponse
    {
        $deck = new DeckOfCards(true);
        $deck->shuffle();
        $session->set('deck', $deck);

        return $this->json($deck->getCards());
    }

    /**
     * Draw one card from the current deck in session.
     *
     * @param SessionInterface $session the session interface
     *
     * @return JsonResponse JSON response with drawn cards and remaining count
     */
    #[Route('/api/deck/draw', name: 'api_draw_one', methods: ['POST'])]
    public function drawOne(SessionInterface $session): JsonResponse
    {
        /** @var DeckOfCards $deck */
        $deck = $session->get('deck', new DeckOfCards(true));
        $drawn = $deck->draw();
        $session->set('deck', $deck);

        return $this->json([
            'drawn' => $drawn,
            'remaining' => $deck->count(),
        ]);
    }

    /**
     * Draw a specified number of cards from the current deck in session.
     *
     * @param SessionInterface $session the session interface
     * @param int              $number  number of cards to draw
     *
     * @return JsonResponse JSON response with drawn cards and remaining count
     */
    #[Route('/api/deck/draw/{number<\d+>}', name: 'api_draw_number', methods: ['POST'])]
    public function drawNumber(SessionInterface $session, int $number): JsonResponse
    {
        /** @var DeckOfCards $deck */
        $deck = $session->get('deck', new DeckOfCards(true));
        $drawn = $deck->draw($number);
        $session->set('deck', $deck);

        return $this->json([
            'drawn' => $drawn,
            'remaining' => $deck->count(),
        ]);
    }
}
