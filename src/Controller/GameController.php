<?php

// src/Controller/GameController.php

namespace App\Controller;

use App\Game\Game21;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

class GameController extends AbstractController
{
    #[Route('/game/play', name: 'game_play')]
    public function play(Request $request, SessionInterface $session): Response
    {
        /** @var Game21 $game */
        $game = $session->get('game') ?? new Game21();

        if ($request->request->has('draw')) {
            $game->playerDraw();
        } elseif ($request->request->has('stay')) {
            $game->playerStay();
        } else {
            // Starta ingen kortdragning automatiskt, vänta på spelarens knapp
        }

        $session->set('game', $game);

        return $this->render('game/play.html.twig', [
            'player' => $game->getPlayer(),
            'bank' => $game->getBank(),
            'status' => $game->getStatus()
        ]);
    }

    #[Route('/game/reset', name: 'game_reset')]
    public function reset(SessionInterface $session): Response
    {
        $session->remove('game');
        return $this->redirectToRoute('game_play');
    }

    #[Route('/game', name: 'game_index')]
    public function index(): Response
    {
        return $this->render('game/start.html.twig');
    }

    #[Route("/game/doc", name: "game_doc")]
    public function doc(): Response
    {
        return $this->render('game/doc.html.twig');
    }
}
