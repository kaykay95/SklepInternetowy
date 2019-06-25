<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use App\Entity\NegotiationExpression;
use App\Form\NegotiationExpressionType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

class NegotiationManagementController extends AbstractController
{
    /**
     * @Route("/negotiationmanagement", name="negotiation_management")
     */
    public function index(Request $request)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'Unable to access this page!');

        $expressions = $this->getDoctrine()
            ->getRepository(NegotiationExpression::class)->findAll();

        $expression = new NegotiationExpression();

        $form = $this->createFormBuilder($expression)
            ->add('expression', null, [
                'attr' => [
                    'autocomplete' => 'off'],
            ])
            ->add('negotiationCategory')
            ->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($expression);
            $entityManager->flush();
            return $this->redirectToRoute('negotiation_management');
        }

        return $this->render('negotiation_management/index.html.twig', [
            'expressions' => $expressions,
            'expressionForm' => $form->createView(),
        ]);
    }
    /**
     * @Route("/deleteexpression/{id}", name="delete_expression")
     */
    public function deleteExpression($id){

        $expression = $this->getDoctrine()
            ->getRepository(NegotiationExpression::class)
            ->find($id);

        $em = $this->getDoctrine()->getManager();

        $em->remove($expression);
        $em->flush();
        return $this->redirectToRoute('negotiation_management');
    }

}
