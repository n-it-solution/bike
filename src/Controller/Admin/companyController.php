<?php

namespace App\Controller\Admin;

use App\Entity\Company;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;

class companyController extends AbstractController
{
    /**
     * @Route("/admin/company", name="admin_company")
     */
    public function index(Request $request)
    {
        $session = New Session();
        if (empty($session->get('user'))){
            return $this->redirectToRoute('admin_index');
        }
        else {
            if($request->getMethod() == 'POST'){
                $company = New Company();
                $company->setName($request->get('name'));
                if($_FILES['doc']['name'] != ""){
                    $info = pathinfo($_FILES['doc']['name']);
                    $ext = $info['extension'];
                    $date = date('mdYhisms', time());
                    $newname = $date . '.' . $ext;
                    $target = 'upload/'.$newname;
                    move_uploaded_file( $_FILES['doc']['tmp_name'], "./".$target);
                    $company->setPic($target);
                }
                $em = $this->getDoctrine()->getManager();
                $em->persist($company);
                $em->flush();
            }
            $company = $this->getDoctrine()->getRepository(Company::class)->findAll();
            return $this->render('admin/company/index.html.twig', [
                'company' => $company,
            ]);
        }
    }
}
