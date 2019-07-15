<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;

class companyController extends AbstractController
{
    /**
     * @Route("/admin/company", name="admin_company")
     */
    public function index()
    {
        $session = New Session();
        if (empty($session->get('user'))){
            return $this->redirectToRoute('admin_index');
        }
        else {
            return $this->render('admin/company/index.html.twig', [
                'controller_name' => 'companyController',
            ]);
        }
    }
}
