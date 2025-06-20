<?php

namespace App\Controller;

use App\Card\CardFactory;
use App\Card\DeckOfCards;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Controller handling card deck operations and session management.
 */
class CardController extends AbstractController
{
    private CardFactory $cardFactory;

    /**
     * Constructor.
     *
     * @param CardFactory $cardFactory factory for creating card instances
     */
    public function __construct(CardFactory $cardFactory)
    {
        $this->cardFactory = $cardFactory;
    }

    /**
     * Render the home page for card operations.
     */
    #[Route('/card', name: 'card_home')]
    public function home(): Response
    {
        return $this->render('card/index.html.twig');
    }

    /**
     * Show the deck of cards from the session or create a new one.
     *
     * @param SessionInterface $session the session interface
     */
    #[Route('/card/deck', name: 'card_deck')]
    public function deck(SessionInterface $session): Response
    {
        $deck = $session->get('deck');

        if (!$deck) {
            $deck = new DeckOfCards($this->cardFactory);
            $session->set('deck', $deck);
        }

        return $this->render('card/deck.html.twig', ['cards' => $deck->getCards()]);
    }

    /**
     * Shuffle the deck of cards and save it to the session.
     *
     * @param SessionInterface $session the session interface
     */
    #[Route('/card/deck/shuffle', name: 'card_shuffle')]
    public function shuffle(SessionInterface $session): Response
    {
        $deck = new DeckOfCards($this->cardFactory);
        $deck->shuffle();
        $session->set('deck', $deck);

        return $this->render('card/deck.html.twig', [
            'cards' => $deck->getCards(),
        ]);
    }

    /**
     * Draw one card from the deck stored in session.
     *
     * @param SessionInterface $session the session interface
     */
    #[Route('/card/deck/draw', name: 'card_draw')]
    public function drawOne(SessionInterface $session): Response
    {
        /** @var DeckOfCards $deck */
        $deck = $session->get('deck', new DeckOfCards($this->cardFactory));
        $drawn = $deck->draw();
        $session->set('deck', $deck);

        return $this->render('card/draw.html.twig', [
            'cards' => $drawn,
            'remaining' => $deck->count(),
        ]);
    }

    /**
     * Draw a specified number of cards from the deck in session.
     *
     * @param SessionInterface $session the session interface
     * @param int              $number  number of cards to draw
     */
    #[Route('/card/deck/draw/{number<\d+>}', name: 'card_draw_number')]
    public function drawNumber(SessionInterface $session, int $number): Response
    {
        /** @var DeckOfCards $deck */
        $deck = $session->get('deck', new DeckOfCards($this->cardFactory));
        $drawn = $deck->draw($number);
        $session->set('deck', $deck);

        return $this->render('card/draw.html.twig', [
            'cards' => $drawn,
            'remaining' => $deck->count(),
        ]);
    }

    /**
     * Clear the entire session.
     *
     * @param SessionInterface $session the session interface
     *
     * @return Response redirects to the card home route
     */
    #[Route('/session/delete', name: 'session_delete')]
    public function deleteSession(SessionInterface $session): Response
    {
        $session->clear();
        $this->addFlash('success', 'Sessionen är raderad!');

        return $this->redirectToRoute('card_home');
    }

    /**
     * Debug endpoint to display all session data.
     *
     * @param SessionInterface $session the session interface
     */
    #[Route('/session', name: 'session_debug')]
    public function sessionDebug(SessionInterface $session): Response
    {
        return $this->render('session/debug.html.twig', [
            'session' => $session->all(),
        ]);
    }
}
