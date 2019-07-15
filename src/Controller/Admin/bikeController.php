<?php

namespace App\Controller\Admin;

use App\Entity\Bike;
use App\Entity\Company;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class bikeController extends AbstractController
{
    /**
     * @Route("/admin/bike", name="admin_bike")
     */
    public function index(Request $request)
    {
        if($request->getMethod() == 'POST'){
            $bike = New Bike();
            $bike->setName($request->get('name'));
            $bike->setPrice($request->get('price'));
            if($_FILES['doc']['name'] != ""){
                $info = pathinfo($_FILES['doc']['name']);
                $ext = $info['extension'];
                $date = date('mdYhisms', time());
                $newname = $date . '.' . $ext;
                $target = 'upload/'.$newname;
                move_uploaded_file( $_FILES['doc']['tmp_name'], "./".$target);
                $bike->setPic($target);
            }
            $bike->setCompany($this->getDoctrine()->getRepository(Company::class)->find($request->get('company')));
            $em = $this->getDoctrine()->getManager();
            $em->persist($bike);
            $em->flush();
            return $this->redirectToRoute('admin_bike');
        }
        $bikes = $this->getDoctrine()->getRepository(Bike::class)->findAll();
        $company = $this->getDoctrine()->getRepository(Company::class)->findAll();
        return $this->render('admin/bike/index.html.twig', [
            'bikes' => $bikes,
            'company' => $company
        ]);
    }

//    /**
//     * @Route("/admin/bike/action/{action}", name="admin_bike_action")
//     */
//    public function bike(){
//
//    }
}
