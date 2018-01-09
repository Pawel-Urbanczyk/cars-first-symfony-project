<?php

namespace CarBundle\Controller;

use Doctrine\DBAL\Types\TextType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class DefaultController extends Controller
{
    /**
     * @Route("/our-cars", name="offer")
     */
    public function indexAction(Request $request)
    {
        $carRespository = $this->getDoctrine()->getRepository('CarBundle:Car');
        $cars           = $carRespository->findCarsWithDetails();
        $form = $this->createFormBuilder()
            ->setMethod('GET')
            ->add('search', \Symfony\Component\Form\Extension\Core\Type\TextType::class, [
                'constraints' => [
                        new NotBlank(),
                        new Length(['min' => 2])
                    ]
            ])
            ->getForm();

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            die('Form Submited');
        }
        return $this->render('CarBundle:Default:index.html.twig',
            [
                'cars' => $cars,
                'form' => $form->createView()
            ]
        );
    }

    /**
     * @param $id
     * @Route("/car/{id}", name="show_car")
     * @return \Symfony\Component\HttpFoundation\Response
     */

    public function showAction($id){
        $carRepository = $this->getDoctrine()->getRepository('CarBundle:Car');
        $car = $carRepository->findCarsWithDetailsById($id);
        return $this->render('CarBundle:Default:show.html.twig', ['car' => $car]);
    }
}
