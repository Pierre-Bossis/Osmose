<?php

namespace App\Controller;

use App\Entity\Personne;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('personne')]

class PersonneController extends AbstractController
{
    #[Route('/', name:'personne.list')]
    public function index(ManagerRegistry $doctrine) : Response{
        $repository = $doctrine->getRepository(Personne::class);
       $personnes = $repository->findAll();
       return $this->render('personne/index.html.twig',['personnes'=>$personnes]);
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
       $personnes = $repository->findBy([],[], $nbre,($page -1)* $nbre);
       return $this->render('personne/index.html.twig',['personnes'=>$personnes]);
    }

    #[Route('/add', name: 'personne.add')]
    public function addPersonne(ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();
        $personne = new Personne();
        $personne->setFirstname('Caroline');
        $personne->setName('Bossis');
        $personne->setAge('29');

 //       $personne2 = new Personne();
 //       $personne2->setFirstname('Nicolas');
 //       $personne2->setName('Bossis');
 //       $personne2->setAge('27');
        // ajouter l'opération d'insertion de la personne dans ma transaction
        $entityManager->persist($personne);
 //       $entityManager->persist($personne2);

        // execute la transation Todo
        $entityManager->flush();


        return $this->render('personne/detail.html.twig', [
            'personne' => $personne,
        ]);
    }
}
