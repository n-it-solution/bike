<?php

namespace App\Controller;

use App\Entity\Bike;
use App\Entity\Company;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{

    private $user = null;
    private $loggedin = false;

    public function __construct()
    {
        if (isset($_SESSION['user'])){
            $this->user = $_SESSION['user'];
            $this->loggedin = true;
        }
    }


    /**
     * @Route("/", name="home_page")
     */
    public function index()
    {
        if ($this->loggedin){
            $comp = $this->getDoctrine()->getRepository(Company::class)->findAll();
            return $this->render('default/index.html.twig', [
                'Companies' => $comp,
            ]);
        }
        return $this->redirectToRoute('user_index');
    }


    /**
     * @Route("/view/bikes/{id}", name="show_bikes")
     */
    public function View_Bikes($id)
    {
        if ($this->loggedin){
            $bikes = $this->getDoctrine()->getRepository(Bike::class)->findBy(
                [
                    'Company' => $id
                ]
            );
            return $this->render('default/bikes.html.twig', [
                'Bikes' => $bikes,
            ]);
        }
        return $this->redirectToRoute('user_index');
    }
}
