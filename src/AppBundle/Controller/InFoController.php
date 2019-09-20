<?php

namespace AppBundle\Controller;

use AppBundle\Entity\GamingConsole;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class InFoController extends Controller
{
    /**
     * @Route("/info", name="list_info")
     */
    public function listAction(Request $request)
    {
        // create a form , 1st thing 1st
        $form = $this->createFormBuilder()
            ->add('Name', TextType::class)
            ->add('Nation', TextType::class)
            ->add('Quantity', TextType::class)
            ->add('Price', NumberType::class)
            ->add('Description', TextType::class)
            ->add('save', SubmitType::class, ['label' => 'Create Product'])
            ->getForm();

        $form->handleRequest($request);

        //2nd , create an instance of entity to interact with the database
        $gam = new GamingConsole();
        $console = $this->getDoctrine()->getRepository("AppBundle:GamingConsole");
        $list_console = $console->findAll(); //show all items of the database in the view

        //3rd , handle the input data in the form, transfer it to database after
        if($form->isSubmitted() && $form->isValid()) {

            $name = $form['Name']->getData();
            $nation = $form['Nation']->getData();
            $quantity = $form['Quantity']->getData();
            $price = $form['Price']->getData();
            $description = $form['Description']->getData();

            //4th, set the input data into the database
            $gam->setName($name);
            $gam->setNation($nation);
            $gam->setQuantity($quantity);
            $gam->setPrice($price);
            $gam->setDescription($description);

            //5th, transfer the data
            $em = $this->getDoctrine()->getManager();
            $em->persist($gam);
            $em->flush();

        }
        //6th, render the form as well as the database.
       return $this->render("ConsoleInfo/List.html.twig", [
           'console' => $list_console,
           'form' => $form->createView()
       ]);

        //The end.
    }
}
