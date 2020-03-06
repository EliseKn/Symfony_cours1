<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Form\CategorieType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CategorieController extends AbstractController
{
    /**
     * @Route("/categorie", name="categorie")
     */

    public function index(Request $request)
    {
        $pdo = $this->getDoctrine()->getManager();
        $categories = $pdo->getRepository(Categorie::class)->findAll();

        $categorie = new Categorie();
        $form2 = $this->createForm(CategorieType::class, $categorie);

        //analyse 
        $form2->handleRequest($request);
        if ( $form2->isSubmitted() && $form2->isValid() ) {
            $pdo->persist($categorie);
            $pdo->flush();
        }

        return $this->render('categorie/index.html.twig', [
            'categories' => $categories,
            'form_categorie_add' => $form2->createView()
        ]);
    }

    /**
     * @Route("/categorie/{id}", name="ma_categorie")
     */
    
    public function categorie(Request $request, Categorie $categorie=null){

        if ($categorie != null) {
            
            $form2 = $this-> createForm(CategorieType::class, $categorie);
            $form2->handleRequest($request);

            if( $form2->isSubmitted() && $form2->isValid() ){
                $pdo = $this->getDoctrine()->getManager();
                $pdo->persist($categorie);
                $pdo->flush();
            }

            return $this->render('categorie/categorie.html.twig', [
                'categorie' => $categorie,
                'form2' => $form2->createView()
            ]);
        }

        else {
            return $this->redirectToRoute('categorie');
        }
    }

    /**
     * @Route("/categorie/delete/{id}", name="delete_categorie")
     */

     public function delete(Categorie $categorie=null){

        if ($categorie != null) {
            $pdo = $this->getDoctrine()->getManager();
            $pdo->remove($categorie); // insertion/modif = "persist" / supression = "remove"
            $pdo->flush();
        }
        else {
            return $this->redirectToRoute('categorie');
        }

        return $this->redirectToRoute('categorie'); 
     }
}
