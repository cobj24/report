<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Controller for generating lucky numbers and simple greetings.
 */
class LuckyController
{
    /**
     * Generate and display a random lucky number between 0 and 100.
     *
     * @return Response HTTP response containing the lucky number in HTML
     */
    #[Route('/lucky/number')]
    public function number(): Response
    {
        $number = random_int(0, 100);

        return new Response(
            '<html><body>Lucky number: '.$number.'</body></html>'
        );
    }

    /**
     * Return a simple greeting message.
     *
     * @return Response HTTP response with greeting in HTML
     */
    #[Route('/lucky/hi')]
    public function hi(): Response
    {
        return new Response(
            '<html><body>Hi to you!</body></html>'
        );
    }
}
