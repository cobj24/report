<?php

namespace App\Controller;

use App\Game\Game21;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Controller for handling the game logic of Game21.
 */
class GameController extends AbstractController
{
    /**
     * Play the game: process player actions like draw or stay.
     *
     * @param Request          $request the current HTTP request
     * @param SessionInterface $session the session to store game state
     *
     * @return Response the response rendering the game play view
     */
    #[Route('/game/play', name: 'game_play')]
    public function play(Request $request, SessionInterface $session): Response
    {
        /** @var Game21 $game */
        $game = $session->get('game') ?? new Game21();

        if ($request->request->has('draw')) {
            $game->playerDraw();
        } elseif ($request->request->has('stay')) {
            $game->playerStay();
        }

        $session->set('game', $game);

        return $this->render('game/play.html.twig', [
            'player' => $game->getPlayer(),
            'bank' => $game->getBank(),
            'status' => $game->getStatus(),
        ]);
    }

    /**
     * Reset the game by removing it from session and redirect to play.
     *
     * @param SessionInterface $session the session interface
     *
     * @return Response redirect response to the game play route
     */
    #[Route('/game/reset', name: 'game_reset')]
    public function reset(SessionInterface $session): Response
    {
        $session->remove('game');

        return $this->redirectToRoute('game_play');
    }

    /**
     * Show the game start page.
     *
     * @return Response the response rendering the start view
     */
    #[Route('/game', name: 'game_index')]
    public function index(): Response
    {
        return $this->render('game/start.html.twig');
    }

    /**
     * Show documentation or instructions for the game.
     *
     * @return Response the response rendering the documentation view
     */
    #[Route('/game/doc', name: 'game_doc')]
    public function doc(): Response
    {
        return $this->render('game/doc.html.twig');
    }
}
