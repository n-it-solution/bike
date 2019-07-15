<?php

namespace App\Controller\Admin;

use App\Entity\Admin;
use App\Entity\Users;
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
     * @Route("/User/index", name="user_index")
     */
    public function User_index(Request $request)
    {
        return $this->render('login.html.twig');
    }



    /**
     * @Route("/User/Login", name="user_login")
     */
    public function User_Login(Request $request)
    {
        $error = '';
        if ($request->getMethod() == "POST")
        {
            $username = $request->get('username');
            $password = $request->get('password');
//            $password = md5($pass);

            $user = $this->getDoctrine()->getRepository(Users::class)->findOneBy(
                ['username' => $username, 'password' => $password]
            );
            if (!empty($user))
            {
//                    echo 1;
                if (session_status() === PHP_SESSION_NONE){
                    session_start();
                }
                $_SESSION['user'] = $user;
                $em = $this->getDoctrine()->getManager();
                $id = $user->getId();
                $myuser = $em->getRepository(Users::class)->find($id);
                $date = date('Y-m-d');
                $myuser->setLastLogin($date);
                $em->flush();

                    return $this->redirectToRoute('super_admin_homepage');
            }
            $error = 'Username Or Password Is Incorrect!';
            return $this->render('adminlogin/login.html.twig',
                ['NotMatch' => $error]);
        }
        return $this->render('adminlogin/login.html.twig',
            ['NotMatch' => $error]);
    }


}
