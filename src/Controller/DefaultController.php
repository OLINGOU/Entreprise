<?php

namespace App\Controller;

use App\Entity\Employe;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DefaultController extends AbstractController
{
    #[Route('/', name: 'default_home')]
    public function home(EntityManagerInterface $entityManager): Response
    {
        # cette instruction nous permet de recuperer en BDD toutes les lignes de la table "employe"
        # ceci est possible grace au Repository, accessible par $entityManager
        $employes = $entityManager->getRepository(Employe::class)->findAll();

        # nous devons maintenant passer la variable $employes à notre vue twig
        return $this->render('default/home.html.twig', [
            'employes' => $employes
        ]);
    }

    
}
