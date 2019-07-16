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
        if (!empty($session->get('admin'))){
            return $this->redirectToRoute('admin_company');
        }
        else {
            if ($request->getMethod() == 'POST') {
                $check = $this->getDoctrine()->getRepository(Admin::class)->findOneBy(['Email' => $request->get('email'), 'Password' => $request->get('pass')]);
                if (!empty($check)) {
                    $session->set('admin', $check);
                    return $this->redirectToRoute('admin_company');
                } else {
                    echo '<script>alert("Email and Password not correct")</script>';
                }
            }
            return $this->render('admin/index/index.html.twig');
        }
    }

    /**
     * @Route("/admin/logout", name="admin_logout")
     */
    public function logout(){
        $session = New Session();
        $session->remove('admin');
        return $this->redirectToRoute('admin_index');
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
     * @Route("/User/register", name="user_register")
     */
    public function User_register(Request $request)
    {
        $success = '';
        $error = '';
        if ($request->getMethod() == "POST"){
            $em = $this->getDoctrine()->getManager();
            $user = new Users();
            $user->setUsername($request->get('username'));
            $user->setEmail($request->get('email'));
            $user->setPassword($request->get('password'));
            $em->persist($user);
            $em->flush();
            $success = "Registered Successfully!";
            return $this->render('register.html.twig',
                [
                    'Success' => $success,
                    'Error' => $error
                ]);
        }else{
            $error = "Not Registered";
            return $this->render('register.html.twig',
                [
                    'Success' => $success,
                    'Error' => $error
                ]);
        }
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
                $em->flush();

                    return $this->redirectToRoute('home_page');
            }
            $error = 'Username Or Password Is Incorrect!';
            return $this->render('login.html.twig',
                ['NotMatch' => $error]);
        }
        return $this->render('login.html.twig',
            ['NotMatch' => $error]);
    }

    /**
     * @Route("/User/logout", name="user_logout")
     */
    public function userLogout(){
        if (session_status() === PHP_SESSION_NONE){
            session_start();
        }
        session_destroy();
        return $this->redirectToRoute('user_login');
    }
}
