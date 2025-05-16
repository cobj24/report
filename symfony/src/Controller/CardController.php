<?php

namespace App\Controller;

use App\Card\DeckOfCards;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class CardController extends AbstractController
{
    #[Route('/card', name: 'card_home')]
    public function index(): Response
    {
        return $this->render('card/index.html.twig');
    }

    #[Route('/card/deck', name: 'card_deck')]
    public function deck(SessionInterface $session): Response
    {
        $deck = $session->get('deck', new DeckOfCards(true));

        return $this->render('card/deck.html.twig', ['cards' => $deck->getCards()]);
    }

    #[Route('/card/deck/shuffle', name: 'card_shuffle')]
    public function shuffle(SessionInterface $session): Response
    {
        $deck = new DeckOfCards(true);
        $deck->shuffle();
        $session->set('deck', $deck);

        return $this->render('card/deck.html.twig', [
            'cards' => $deck->getCards(),
        ]);
    }

    #[Route('/card/deck/draw', name: 'card_draw')]
    public function drawOne(SessionInterface $session): Response
    {
        /** @var DeckOfCards $deck */
        $deck = $session->get('deck', new DeckOfCards(true));
        $drawn = $deck->draw();
        $session->set('deck', $deck);

        return $this->render('card/draw.html.twig', [
            'cards' => $drawn,
            'remaining' => $deck->count(),
        ]);
    }

    #[Route('/card/deck/draw/{number<\d+>}', name: 'card_draw_number')]
    public function drawNumber(SessionInterface $session, int $number): Response
    {
        /** @var DeckOfCards $deck */
        $deck = $session->get('deck', new DeckOfCards(true));
        $drawn = $deck->draw($number);
        $session->set('deck', $deck);

        return $this->render('card/draw.html.twig', [
            'cards' => $drawn,
            'remaining' => $deck->count(),
        ]);
    }

    #[Route('/session/delete', name: 'session_delete')]
    public function deleteSession(SessionInterface $session): Response
    {
        $session->clear();
        $this->addFlash('success', 'Sessionen är raderad!');

        return $this->redirectToRoute('card_home');
    }

    #[Route('/session', name: 'session_debug')]
    public function sessionDebug(SessionInterface $session): Response
    {
        return $this->render('session/debug.html.twig', [
            'session' => $session->all(),
        ]);
    }
}
