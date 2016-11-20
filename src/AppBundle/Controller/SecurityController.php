<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * @Route("/admin")
 */
class SecurityController extends Controller
{
    /**
     * @Route("/login")
     */
    public function login()
    {
        $utils = $this->get('security.authentication_utils');

        return $this->render('admin/login.html.twig', [
            'error' => $utils->getLastAuthenticationError()
        ]);
    }

    /**
     * @Route("/login_check")
     */
    public function login_check()
    {

    }

    /**
     * @Route("/logout", name="app.admin_logout")
     */
    public function logout()
    {

    }
}