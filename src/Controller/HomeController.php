<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController {
    #[Route("/" ,name:"home")]
   
    function index(Request $request , EntityManagerInterface $em , UserPasswordHasherInterface $hasher): Response {
    //    $user = new User();
    //    $user->setEmail('fatima@jdidi.fr')->setUsername('fatimazahra')->setPassword($hasher->hashPassword($user, 'jdidi'))
    //    ->setRoles([]);
    //    $em->persist($user);
    //    $em->flush();
        return $this->render('home/index.html.twig');

    }
}
 // function index() : Response {
    //     return new Response ("hello " . $_GET['name']);
//  return new Response ("hello " . $request->query->get('name'));
    // }