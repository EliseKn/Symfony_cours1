<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Form\ProduitType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ProduitController extends AbstractController
{
    /**
     * @Route("/", name="produit")
     */
    public function index(Request $request)
    {
        // recupère la Doctrine (gestion de la BDD)
        $pdo = $this->getDoctrine()->getManager();

        $produits = $pdo->getRepository(Produit::class)->findAll();

        /**
         * -> findOneBy(['id' => 2])
         * -> findBy(['nom' => 'nom du produit'])
         */

        $produit = new Produit();
        $form = $this->createForm(ProduitType::class, $produit);

        //analyse de la requête HTTP
        $form->handleRequest($request);
        if( $form->isSubmitted() && $form->isValid() ){
            //le formulaire est envoyé, on le sauvegarde
            $pdo->persist($produit); //prépare
            $pdo->flush(); //exécute
        }

        return $this->render('produit/index.html.twig', [
            'produit' => $produits,
            'form_produit_add' => $form->createView()
        ]);
    }

    /**
     * @Route("/{id}", name="mon_produit")
     */

     public function produit(Request $request, Produit $produit=null){
        
        if($produit != null ){ 
            // si le produit existe 

            $form = $this->createForm(ProduitType::class, $produit);
            $form->handleRequest($request);

            if( $form->isSubmitted() && $form->isValid() ){
                $pdo = $this->getDoctrine()->getManager();
                $pdo->persist($produit);
                $pdo->flush();
            }

            return $this->render('produit/produit.html.twig', [
                'produit' => $produit,
                'form' => $form->createView()
            ]);
        }
        else {
            //le produit n'existe pas
            return $this-> redirectToRoute('produit');
        }
    }

        /**
     * @Route("/delete/{id}", name="delete_produit")
     */

    public function delete(Produit $produit=null){

        if ($produit != null) {
            $pdo = $this->getDoctrine()->getManager();
            $pdo->remove($produit); // insertion/modif = "persist" / supression = "remove"
            $pdo->flush();
        }

        return $this->redirectToRoute('produit'); 
     }
}

