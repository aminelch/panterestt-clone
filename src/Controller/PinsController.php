<?php

namespace App\Controller;

use App\Entity\Pin;
use App\Repository\PinRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PinsController extends AbstractController
{
    /**
     * @Route("/", name="app_home")
     */
    public function index(PinRepository $repository): Response
    {

        $pins = $repository->findBy([], ["createdAt" => "DESC"]);

        return $this->render('pins/index.html.twig', compact('pins'));
    }

    /**
     * @Route("/pins/{id<[0-9]+>}", name="app_pins_show", methods="get")
     */
    public function show(Pin $pin): Response
    {
        dump($pin);
        return $this->render('pins/show.html.twig', compact('pin'));
    }

    /**
     * @Route("/pins/create", name="app_pins_create", methods={"GET","POST"})
     */
    public function create(Request $request, EntityManagerInterface $manager): Response
    {
        $pin = new Pin;

        $form = $this->createFormBuilder($pin)
            ->add("title", TextType::class)
            ->add("description", TextareaType::class)
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($pin);
            $this->addFlash('success', "Pin added with success");
            return $this->redirectToRoute('app_home');
        }
        return $this->render('pins/create.html.twig', [
            "form" => $form->createView()
        ]);
    }


    /**
     * @Route("/pins/{id<[0-9]+>}/edit", name="app_pins_edit", methods={"GET","POST"})
     */
    public function edit(Pin $pin, Request $request, EntityManagerInterface $manager): Response
    {
        dump($pin); 

        $form = $this->createFormBuilder($pin)
            ->add("title", TextType::class)
            ->add("description", TextareaType::class)
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()  ) {
            $manager->flush($pin);
            return $this->redirectToRoute('app_home');
        }


        return $this->render('pins/edit.html.twig', [
            'form' => $form->createView(),
            'pin'=>$pin
        ]);
    }
}
