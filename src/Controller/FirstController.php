<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class FirstController extends AbstractController
{
    #[Route('/first', name: 'first')]
    public function index(): Response
    {
        return $this->render('first/index.html.twig', [
            'name' => 'Bossis',
            'firstname' => 'Pierre'
        ]);
    }

    #[Route('/sayHello/{name}/{firstname}', name: 'say.hello')]
    public function sayHello(Request $request,$name,$firstname): Response
    {
        //dd($request);
        return $this->render('first/hello.html.twig',[
            'nom'=>$name,
            'prenom'=>$firstname
        ]);
        //return $this->forward('App\Controller\FirstController::index');
    }
}