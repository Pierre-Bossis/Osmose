<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

#[route("/todo")]

class TodoController extends AbstractController
{
    #[Route('/', name: 'app_todo')]
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

    #[Route(
        '/add/{name?titre}/{content?contenu}',
        name:'todo.add'
        )]
    //   defaults: ['name'=>'titre','content'=>'contenu']
    public function addTodo(Request $request, $name, $content): RedirectResponse {
        $session = $request->getsession();
        //vérifier si j'ai mon tableau de todo dans la session
        if($session->has('todos')){
            //si oui
            //vérifier si on a deja un todo avec le même name
            $todos = $session->get('todos');
            if(isset($todos[$name])){
                //si oui afficher erreur
                $this->addFlash('error',"le todo d id $name existe déjà dans la liste");
            } else{
                //si non on l'ajoute et on affiche un message success
                $todos[$name] = $content;
                $session->set('todos',$todos);         
                $this->addFlash('success',"le todo d id $name a été ajouté avec succès");      
            }

        } else{
            //si non
            //afficher une erreur et rediriger vers controller index
            $this->addFlash('error',"la liste des todo n est pas encore initialisée");
        }

        return $this->redirectToRoute('todo');
    }


    #[Route('/update/{name}/{content}',name:'todo.update')]
    public function updateTodo(Request $request, $name, $content): RedirectResponse{
        $session = $request->getsession();
        //vérifier si j'ai mon tableau de todo dans la session
        if($session->has('todos')){
            //si oui
            //vérifier si on a deja un todo avec le même name
            $todos = $session->get('todos');
            if(!isset($todos[$name])){
                //si oui afficher erreur
                $this->addFlash('info',"le todo d id $name existe pas dans la liste");
            } else{
                //si non on l'ajoute et on affiche un message success
                $todos[$name] = $content;
                $session->set('todos',$todos);         
                $this->addFlash('success',"le todo d id $name a été modifié avec succès");      
            }

        } else{
            //si non
            //afficher une erreur et rediriger vers controller index
            $this->addFlash('error',"la liste des todo n est pas encore initialisée");
        }

        return $this->redirectToRoute('todo');
    }


    #[Route('/delete/{name}',name:'todo.delete')]
    public function deleteTodo(Request $request, $name): RedirectResponse{
        $session = $request->getsession();
        //vérifier si j'ai mon tableau de todo dans la session
        if($session->has('todos')){
            //si oui
            //vérifier si on a deja un todo avec le même name
            $todos = $session->get('todos');
            if(!isset($todos[$name])){
                //si oui afficher erreur
                $this->addFlash('info',"le todo d id $name n existe pas dans la liste");
            } else{
                //si non on l'ajoute et on affiche un message success
                unset($todos[$name]);
                $session->set('todos',$todos);         
                $this->addFlash('success',"le todo d id $name a été supprimé avec succès");      
            }

        } else{
            //si non
            //afficher une erreur et rediriger vers controller index
            $this->addFlash('error','la liste des todo n est pas encore initialisée');
        }

        return $this->redirectToRoute('todo');
    }


    #[Route('/reset',name:'todo.reset')]
    public function resetTodo(Request $request): RedirectResponse{
        $session = $request->getsession();
        $session->remove('todos');
        return $this->redirectToRoute('todo');
    }
}