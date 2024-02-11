<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class AuthenticationController extends AbstractController
{
    #[Route('/api/login', name: 'api_login')]
    public function login(): JsonResponse
    {
        $user = $this->getUser();

        if (!$user) {
            return $this->json([
                'error' => 'Invalid login request: check that the Content-Type header is "application/json".',
            ], 400);
        }

        return $this->json([
            'username' => $user->getUserIdentifier(),
        ]);
    }

    #[Route('/api/logout', name: 'api_logout', methods: ['POST'])]
    public function logout()
    {
        throw new \Exception('Should not be reached. The route should be intercepted by the firewall.');
    }

    #[Route('/api/authenticated', name: 'api_authenticated', methods: ['GET'])]
    public function authenticated()
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
    }
}
