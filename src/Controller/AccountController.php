<?php

namespace App\Controller;

use App\Form\AccountType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccountController extends AbstractController
{
    /**
     * @Route("/account", name="app_user_account", methods="GET")
     */
    public function account():Response
    {
        return $this->render('account/account.html.twig', ['user' => $this->getUser()]);
    }

    /**
     * @Route("/account/edit", name="app_user_account_edit", methods="GET|POST")
     */
    public function edit(Request $request):Response
    {
        $user= $this->getUser();
        $form = $this->createForm(AccountType::class,$user);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            dump($form->getData()); 
            die('processing');
        }

        return $this->render('account/edit.html.twig', [
            'form' => $form->createView(), 
            'user' => $this->getUser()
        ]);
    }
}
