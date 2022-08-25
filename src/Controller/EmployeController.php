<?php

namespace App\Controller;

use DateTime;
use App\Entity\Employe;
use App\Form\EmployeFormType;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class EmployeController extends AbstractController
{
    /*
    ------ 1 _ Action
    Lorsque vous créez une fonction dans un Controller, cela devient une "action".
    Une "action" commence toujours par un verbe (sauf 'home'). La convention de nommage est le camelCase !
    ------ 2 _ Injection de dépendances
    Dans les parenthèses d'une fonction ("action") vous allez, peut-être, avoir besoin d'outils (objet).
    Pour vous en servir, dans Symfony, on injectera des dépendances. Cela revient à les définir comme 'paramètres'.
    ------ 3 _ Route
    La route, depuis PHP 8, peut s'écrire sous forme d'Attribut, cela permet de dissocier des Annotations !
    Cela se traduit par une syntaxe différente. Une Route prendra TOUJOURS 3 arguments :
        * a - une URI, qui est un bout d'URL.
        * b - un 'name', qui permet de nommer la route pour s'en servir plus tard.
        * c - une méthode HTTP, qui autorise telle ou telle requête HTTP. Question de sécurité !
    !!! TOUTES VOS ROUTES DOIVENT ÊTRE COLLÉES À VOTRE FONCTION !!!
     */

    #[Route('/ajouter-un-employe', name: 'create_employe', methods: ['GET','POST'])]
    public function createEmploye(Request $request, EntityManagerInterface $entityManager): Response
    {
        // ----------------------------------- 1ere Méthode : GET --------------------------------- //

        #Instanciation d'un objet de type Employe
        $employe = new Employe();

        # Nous créons une variable $form qui contiendra le formulaire créé par la méthode createForm()
        # Le mécanisme d'auto-hydratation se fait concrétement par l'ajout d'un second argument
        # dans la méthode createForm(). On passera $employe en argument.
        $form = $this->createForm(EmployeFormType::class, $employe);

        # Pour que le mécanisme de base de symfony soit respecté,on devra manipuler la requête avec la methode handleRequest() et l'objet $request 
        $form->handleRequest($request);
        // ----------------------------------- 2eme Méthode : POST --------------------------------- //
        if($form->isSubmitted() && $form->isValid()) {

            #nous devons renseigner manuellement une valeur pour la propriété createdAt car cette valeur ne peut pas être "NULL" et n'est setter par le formulaire
            $employe->setCreatedAt(new DateTime());

            # nous inserons en BDD grace à notre  $entityManager et la methode persist
            $entityManager->persist($employe);

            # Nous devrons "vider" (trad de flush) l'entityManager pour réellement ajouter une ligne en BDD.
            $entityManager->flush();
            
            # pour terminer nous devons rediriger l'utilisateur vers une page html
            # Nous utilisons la méthode redirectToRoute() pour faire la redirection.
            return $this->redirectToRoute('default_home');
        }

        // ----------------------------------- 1ere Méthode : GET --------------------------------- //

        # On peut directement 'return' pour rendre la vue (page HTMl) du formulaire.
        return $this->render('form/employe.html.twig', [
            'form_employe' => $form->createView()
        ]);

    }

}
