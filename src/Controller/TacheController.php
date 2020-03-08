<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Tache;
use App\Form\TacheType;

class TacheController extends AbstractController
{
    /**
     * @Route("/tache", name="tache")
     */
    public function index(Request $request)

    {   $pdo = $this->getDoctrine()->getManager();
 
        $taches = $pdo->getRepository(Tache::class)->findAll();

         $tache = new Tache();
         $form = $this->createForm(TacheType::class, $tache);
    
         $form->handleRequest($request);
         if($form->isSubmitted() && $form->isValid()){
             $pdo->persist($tache); 
             $pdo->flush(); 
         }
           
        return $this->render('tache/index.html.twig', [
            'taches' => $taches,
            'form_tache_new' => $form->createView()
        ]);
    }

        /**
     * @Route("/tache/{id}", name="une_tache")
     */

    public function tache(Request $request,  Tache $tache){

        $form = $this->createForm(TacheType::class, $tache);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $pdo = $this->getDoctrine()->getManager();
            $pdo->persist($tache); 
            $pdo->flush(); 
        }
        return $this->render('tache/tache.html.twig', [
            'tache' => $tache,
            'form' => $form->createView()
        ]);
    }

    /**
     *@Route("/tache/delete/{id}", name="delete_tache")  
     */ 
    public function delete(Utilisateur $tache=null){
        if($tache !=null){
            //On a trouvé un utilisateur, on le supprime 
            $pdo=$this->getDoctrine()->getManager();
            $pdo->remove($tache);
            $pdo->flush();
        }

        return $this->redirectToRoute('tache');
    }
}
