<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class TodoController extends AbstractController
{
    #[Route('/todo', name: 'app_todo')]
    public function index(Request $request): Response
    {
        $session = $request->getSession();
        
        //afficher notre tableau de todo
        //sinon je l'initialise puis l'affiche
        if(!$session->has('todos')){
            $todos = [
                'achat'=>'acheter une clé usb',
                'cours'=>'finaliser mon cours',
                'correction'=>'corriger mes examens'
            ];
            $session->set('todos',$todos);
            $this->addFlash('info',"la liste des Todo vient d'être initialisée");
        }

        //si j'ai mon tableau de mon todo dans ma session, je ne fais que l'afficher
        return $this->render('todo/index.html.twig');
    }

    #[Route('/todo/add/{name}/{content}',name:"todo.add")]
    public function addTodo(Request $request, $name, $content){
        $session = $request->getsession();
        //vérifier si j'ai mon tableau de todo dans la session
        if($session->has('todos')){
            //si oui
            //vérifier si on a deja un todo avec le même name
            $todos = $session->get('todos');
            if(isset($todos[$name])){
                //si oui afficher erreur
                $this->addFlash('info',"le todo d'id $name existe déjà dans la liste");
            } else{
                //si non on l'ajoute et on affiche un message success
                $todos[$name] = $content;
                $session->set('todos',$todos);         
                $this->addFlash('success',"le todo d'id $name a été ajouté avec succès");      
            }

        } else{
            //si non
            //afficher une erreur et rediriger vers controller index
            $this->addFlash('error',"la liste des todo n'est pas encore initialisée");
        }

        return $this->redirectToRoute('todo');
    }
}