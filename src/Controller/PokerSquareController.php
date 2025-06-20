<?php

namespace App\Controller;

use App\Game\PokerSquareGame;
use App\Service\HighscoreService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class PokerSquareController extends AbstractController
{
    #[Route('/proj/play', name: 'proj_play')]
    public function play(SessionInterface $session): Response
    {
        $game = $session->get('poker_game');

        if (!$game instanceof PokerSquareGame) {
            $game = new PokerSquareGame();
            $session->set('poker_game', $game);
        }
        return $this->render('projekt/play.html.twig', [
            'grid' => $game->getGrid(),
            'nextCard' => $game->getCurrentCard(),
            'gameOver' => $game->isGameOver(),
            'scores' => $game->getGridScores(),
            'totalScore' => $game->getTotalScore(),
            'suggestedMove' => $game->getSuggestedMove(),
        ]);
    }

    #[Route('/proj/place', name: 'proj_place_card', methods: ['POST'])]
    public function place(Request $request, SessionInterface $session): Response
    {
        /** @var PokerSquareGame $game */
        $game = $session->get('poker_game');
        $position = $request->request->get('position');

        if ($game && $position) {
            $game->placeCard($position);
            $session->set('poker_game', $game);
        }

        return $this->redirectToRoute('proj_play');
    }

    #[Route('/proj/save-score', name: 'proj_save_score', methods: ['POST'])]
    public function saveScore(Request $request, SessionInterface $session, HighscoreService $highscoreService): Response
    {
        /** @var PokerSquareGame $game */
        $game = $session->get('poker_game');
        $name = $request->request->get('player_name');
        $scores = $game->getGridScores();
        $total = array_sum($scores['rows']) + array_sum($scores['cols']);

        $highscoreService->addScore($name, $total);
        $session->remove('poker_game');

        return $this->redirectToRoute('proj_highscores');
    }

    #[Route('/proj/highscores', name: 'proj_highscores')]
    public function highscores(HighscoreService $highscoreService): Response
    {
        $scores = $highscoreService->getHighscores();

        return $this->render('projekt/highscores.html.twig', [
            'highscores' => $scores
        ]);
    }

    #[Route('/proj/reset', name: 'proj_reset')]
    public function reset(SessionInterface $session): Response
    {
        $session->remove('poker_game');

        return $this->redirectToRoute('proj_play');
    }
}
