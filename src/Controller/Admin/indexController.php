<?php

namespace App\Controller\Admin;

use App\Entity\Admin;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;

class indexController extends AbstractController
{
    /**
     * @Route("/admin", name="admin_index")
     */
    public function index(Request $request)
    {
        $session = New Session();
        if (!empty($session->get('user'))){
            return $this->redirectToRoute('admin_company');
        }
        else {
            if ($request->getMethod() == 'POST') {
                $check = $this->getDoctrine()->getRepository(Admin::class)->findOneBy(['Email' => $request->get('email'), 'Password' => $request->get('pass')]);
                if (!empty($check)) {
                    $session->set('user', $check);
                } else {
                    echo '<script>alert("Email and Password not correct")</script>';
                }
            }
            return $this->render('admin/index/index.html.twig');
        }
    }
}
