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

    #[Route('/template', name:'template')]
    public function template(){
        return $this->render('template.html.twig');
    }

    #[Route('/sayHello/{name}/{firstname}', name: 'say.hello')]
    public function sayHello(Request $request,$name,$firstname): Response
    {
        //dd($request);
        return $this->render('first/hello.html.twig',[
            'nom'=>$name,
            'prenom'=>$firstname,
            'path'=>'    '
        ]);
        //return $this->forward('App\Controller\FirstController::index');
    }


   /* #[route(
        'multi/{entier1}/{entier2}',
        name: 'multiplication',
        requirements: ['entier1'=>'\d+','entier2'=>'\d+'] // \d+ = entier
        )]*/
    #[route('multi/{entier1<\d+>}/{entier2<\d+>}',name:'multiplication')]
    public function multiplication($entier1,$entier2){
        $resultat = $entier1*$entier2;
        return new Response("<h1>$resultat</h1>");
    }
}