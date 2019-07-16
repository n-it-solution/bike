<?php

namespace App\Controller;

use App\Entity\Bike;
use App\Entity\Company;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class SearchController extends AbstractController
{
    /**
     * @Route("/search", name="search")
     */
    public function index(Request $request)
    {
        $search = $request->get('search');
        $show = false;
        if($search){
            $company = $this->getDoctrine()->getRepository(Company::class)->findBy(['Name' => $search]);
            $bike = $this->getDoctrine()->getRepository(Bike::class)->findBy(['Name' => $search]);
            $show = true;
            return $this->render('search/index.html.twig', [
                'show' => $show,
                'company' => $company,
                'bike' => $bike
            ]);
        }
        return $this->render('search/index.html.twig', [
            'show' => $show,
        ]);
    }
}
