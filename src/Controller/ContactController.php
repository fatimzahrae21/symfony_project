<?php

namespace App\Controller;

use App\DTO\ContactDTO;
use App\Form\ContactType;
use PhpParser\Node\Stmt\TryCatch;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Attribute\Route;

class ContactController extends AbstractController
{
    #[Route('/contact', name: 'contact')]
    public function contact(Request $request, MailerInterface $mailer): Response
    {


        $data = new ContactDTO();
// TODO : supprimer ca 

// $data->name = 'John Doe';
// $data->email = 'john@doe.fr';
// $data->message = 'Super site';



        $form = $this->createForm(ContactType::class);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            try {
            // envoyer email
            $mail = (new TemplatedEmail())
            ->to($data->service)
            ->from($data->email)
            ->subject('Demande de contact')
            ->htmlTemplate('emails/contact.html.twig')
            ->context(['data' => $data]);
           
                $mailer->send($mail);
                $this->addFlash('success' , 'Votre email a bien ete envoye');
               return $this->redirectToRoute('contact');

            } catch (\Exception $e){
                $this->addFlash('danger' , 'Impossible denvoyer votre email');
            }
           
           
        

        }
        return $this->render('contact/contact.html.twig', [
            'form' => $form,
        ]);
    }
}
