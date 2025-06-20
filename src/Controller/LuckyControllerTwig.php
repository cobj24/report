<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Controller for rendering various Twig templates related to lucky numbers and site pages.
 */
class LuckyControllerTwig extends AbstractController
{
    /**
     * Display a random lucky number between 0 and 100.
     *
     * @return Response rendered Twig template with the lucky number
     */
    #[Route('/lucky', name: 'lucky_number')]
    public function number(): Response
    {
        $number = random_int(0, 100);

        $data = [
            'number' => $number,
        ];

        return $this->render('lucky_number.html.twig', $data);
    }

    /**
     * Render the homepage for the user.
     *
     * @return Response rendered Twig template for the homepage
     */
    #[Route('/', name: 'me')]
    public function me(): Response
    {
        return $this->render('me.html.twig');
    }

    /**
     * Render the about page.
     *
     * @return Response rendered Twig template for the about page
     */
    #[Route('/about', name: 'about')]
    public function about(): Response
    {
        return $this->render('about.html.twig');
    }

    /**
     * Render the report page.
     *
     * @return Response rendered Twig template for the report page
     */
    #[Route('/report', name: 'report')]
    public function report(): Response
    {
        return $this->render('report.html.twig');
    }

    /**
     * Render the library page.
     *
     * @return Response rendered Twig template for the library page
     */
    #[Route('/library', name: 'library')]
    public function library(): Response
    {
        return $this->render('library.html.twig');
    }

    /**
     * Render the metrics page.
     *
     * @return Response rendered Twig template for the metrics page
     */
    #[Route('/metrics', name: 'metrics')]
    public function metrics(): Response
    {
        return $this->render('metrics.html.twig');
    }

    /**
     * Render the projekt page.
     *
     * @return Response rendered Twig template for the projekt page
     */
    #[Route('/projekt', name: 'projekt')]
    public function projekt(): Response
    {
        return $this->render('projekt.html.twig');
    }
}
