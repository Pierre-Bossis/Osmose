<?php

namespace App\Controller;

use App\Entity\Personne;
use Doctrine\Persistence\ManagerRegistry;
use App\Form\PersonneType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

#[Route('personne')]

class PersonneController extends AbstractController
{
    #[Route('/', name:'personne.list')]
    public function index(ManagerRegistry $doctrine) : Response{
        $repository = $doctrine->getRepository(Personne::class);
    $personnes = $repository->findAll();
    return $this->render('personne/index.html.twig',['personnes'=>$personnes]);
    }

    #[Route('/alls/age/{ageMin}/{ageMax}', name:'personne.list.age')]
    public function personneByAge(ManagerRegistry $doctrine, $ageMin, $ageMax) : Response{
        $repository = $doctrine->getRepository(Personne::class);
    $personnes = $repository->findPersonnesByAgeInterval($ageMin,$ageMax);
    return $this->render('personne/index.html.twig',['personnes'=>$personnes]);
    }

    #[Route('/stats/age/{ageMin}/{ageMax}', name:'personne.list.age')]
    public function statsPersonnes(ManagerRegistry $doctrine, $ageMin, $ageMax) : Response{
        $repository = $doctrine->getRepository(Personne::class);
    $stats = $repository->statsPersonnesByAgeInterval($ageMin,$ageMax);
    return $this->render('personne/stats.html.twig',[
        'stats'=>$stats[0],
        'ageMin'=>$ageMin,
        'ageMax'=>$ageMax
        ]);
    }



    #[Route('/{id<\d+>}', name:'personne.detail')]
    public function detail(Personne $personne = null) : Response{ //$id
      //  $repository = $doctrine->getRepository(Personne::class);
      //  $personne = $repository->find($id);
        if(!$personne){
            $this->addFlash('error',"La personne n'existe pas.");
            return $this->redirectToRoute('personne.list');
        }

       return $this->render('personne/detail.html.twig',['personne'=>$personne]);
    }

    #[Route('/alls/{page?1}/{nbre?12}', name:'personne.list.alls')]
    public function indexAlls(ManagerRegistry $doctrine, $page, $nbre) : Response{
        $repository = $doctrine->getRepository(Personne::class);
        $nbPersonne = $repository->count([]);
        $nbPage = ceil($nbPersonne / $nbre);
        $personnes = $repository->findBy([],[], $nbre,($page -1)* $nbre);

       return $this->render('personne/index.html.twig',[
        'personnes'=>$personnes,
         'isPaginated'=>true,
         'nbPage'=>$nbPage,
         'page'=>$page,
         'nbre'=>$nbre
    ]);
    }

    #[Route('/edit/{id?0}', name: 'personne.edit')]
    public function addPersonne(Personne $personne=null,ManagerRegistry $doctrine, Request $request ): Response
    {
        $new = false;

        if(!$personne){
            $new = true;
            $personne = new Personne();
        }
        // $personne est l'image de notre formulaire
        $form = $this->createForm(PersonneType::class,$personne);
        $form->remove('createdAt');
        $form->remove('updatedAt');
        //Mon formulaire va aller traiter la requete
        $form->handleRequest($request);
        //est-ce que le formulaire a été soumis ?
        if($form-> isSubmitted()){
            // si oui ajouter l'objet personne dans la base de donnée.
            $manager = $doctrine->getManager();
            $manager->persist($personne);
            $manager->flush();
            // afficher un message de succès
            if($new){
                $message = " a été ajouté avec succès.";
            } else{
                $message = " a été mis à jour avec succès.";
            }
            $this->addFlash('success',$personne->getName(). $message);
            // rediriger vers la liste des personnes
            return $this->redirectToRoute('personne.list');

        } else{
            // si non afficher le formulaire 
            return $this->render('personne/add-personne.html.twig', [
                'form'=>$form->createView()
            ]);
        }
    }

    #[Route('/delete/{id}', name:'personne.delete')]
    public function deletePersonne(Personne $personne = null, ManagerRegistry $doctrine): RedirectResponse{
        // récuperer la personne
        if($personne){
            // si la personne existe => le supprimer et retourner un flashMessage de succès
            $manager = $doctrine->getManager();
            //ajoute la fonction de suppression dans la transaction
            $manager->remove($personne);
            //executer la transaction
            $manager->flush();
            $this->addFlash('success',"La personne a été supprimée avec succès.");
        } else{
        //sinon => retourner un flashMessage d'erreur
            $this->addFlash('error',"Personne inexistante.");
        }
        return $this->redirectToRoute('personne.list');


    }

    #[Route('/update/{id}/{name}/{firstname}/{age}', name:'personne.update')]
    public function updatePersonne(Personne $personne = null, $name, $firstname, $age, ManagerRegistry $doctrine): RedirectResponse{
        // vérifier que la personne à mettre à jour existe.
        if($personne){
            // si la personne existe => mettre à jour notre personne + message succès
            $personne->setName($name);
            $personne->setFirstname($firstname);
            $personne->setAge($age);
            $manager = $doctrine->getManager();
            $manager->persist($personne);
            $manager->flush();
            $this->addFlash('success','La personne a été mise à jour avec succès.');
        } else{
            // sinon => déclencher message d'erreur
            $this->addFlash('error',"Personne inexistante.");
        }
        return $this->redirectToRoute('personne.list.alls');
    }
}
