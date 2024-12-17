<?php

namespace App\Controller\API;

use Symfony\Bridge\Doctrine\Security\User\EntityUserProvider;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class UserController extends AbstractController {

    #[Route('/api/me')]
    #[IsGranted("ROLE_USER")]
    public function me(){
        return $this->json($this->getUser());

    }
}