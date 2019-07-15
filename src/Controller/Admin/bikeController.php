<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class bikeController extends AbstractController
{
    /**
     * @Route("/admin/bike", name="admin_bike")
     */
    public function index()
    {
        return $this->render('admin/bike/index.html.twig', [
            'controller_name' => 'bikeController',
        ]);
    }
}
