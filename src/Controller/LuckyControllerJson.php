<?php

namespace App\Controller;

use App\Card\DeckOfCards;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
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

    #[Route('/api/game', name: 'api_game')]
    public function gameStatus(SessionInterface $session): JsonResponse
    {
        $game = $session->get('game');

        if (!$game) {
            return $this->json([
                'status' => 'no_game',
                'message' => 'Ingen pågående spelomgång hittades.'
            ]);
        }

        $playerHand = array_map(fn ($card) => (string)$card, $game->getPlayer()->getHand()->getCards());
        $bankHand = array_map(fn ($card) => (string)$card, $game->getBank()->getHand()->getCards());

        return $this->json([
            'status' => $game->getStatus(),
            'player' => [
                'score' => $game->getPlayer()->getScore(),
                'hand' => $playerHand
            ],
            'bank' => [
                'score' => $game->getBank()->getScore(),
                'hand' => $bankHand
            ]
        ]);
    }
}
