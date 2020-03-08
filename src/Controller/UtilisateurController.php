<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Utilisateur;
use App\Form\UtilisateurType;
use Symfony\Component\HttpFoundation\Request;

class UtilisateurController extends AbstractController
{
    /**
     * @Route("/", name="utilisateur")
     */
    public function index(Request $request)
    
    {   
         // Récupère Doctrine (service de gestion de BDD)
         $pdo = $this->getDoctrine()->getManager();

         // Récupère tous les utilisateurs
         $utilisateurs = $pdo->getRepository(Utilisateur::class)->findAll();
         /**
          * ->findOneBy(['id' => 2])
          * ->findBy(['nom' => 'Nom du utilisateur'])
          */
          $utilisateur = new Utilisateur();
          $utilisateur->setDateInscription(new \DateTime('now'));
          $form = $this->createForm(UtilisateurType::class, $utilisateur);
          
          // Analyse la requete HTTP
          $form->handleRequest($request);
          if($form->isSubmitted() && $form->isValid()){
              // Le formulaire a été envoyé, on le sauvegarde
              $pdo->persist($utilisateur); // prepare
              $pdo->flush(); // execute
          }

        return $this->render('utilisateur/index.html.twig', [
            'utilisateurs' => $utilisateurs,
            'form_utilisateur_new' => $form->createView()
        ]);
    }

           /**
     * @Route("/utilisateur/{id}", name="utilisateur")
     */

    public function utilisateur(Utilisateur $utilisateur=null, Request $request){

        $form = $this->createForm(UtilisateurType::class, $utilisateur);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $pdo = $this->getDoctrine()->getManager();
            $pdo->persist($utilisateur); 
            $pdo->flush(); 
        }
        return $this->render('utilisateur/utilisateur.html.twig', [
            'utilisateur' => $utilisateur,
            'form' => $form->createView()
        ]);


    }
    /**
     *@Route("/utilisateur/delete/{id}", name="delete_utilisateur")  
     */ 
    public function delete(Utilisateur $utilisateur=null){
        if($utilisateur !=null){
            //On a trouvé un utilisateur, on le supprime 
            $pdo=$this->getDoctrine()->getManager();
            $pdo->remove($utilisateur);
            $pdo->flush();
        }

        return $this->redirectToRoute('utilisateur');
    }
}
