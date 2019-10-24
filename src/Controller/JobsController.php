<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class JobsController extends AbstractController
{
    /**
     * @Route("/jobs", name="jobs")
     */
    public function index()
    {
        $user = $this->getUser();

        return $this->json([
            'message' => 'Jobs controller! (' . $user->getEmail() .')'
        ]);
    }
}
