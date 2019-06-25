<?php

namespace App\Controller;

use App\Entity\Negotiation;
use App\Form\EnterLinkFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class EnterLinkController extends AbstractController
{
    /**
     * @Route("/enterlink/{id}", name="enter_link")
     */
    public function index(Negotiation $negotiation, Request $request, EntityManagerInterface $em, $id)
    {

        $negotiation = $this->getDoctrine()
            ->getRepository(Negotiation::class)
            ->find($id);

        $lastNegotiation = $this->getDoctrine()
            ->getRepository(Negotiation::class)
            ->findOneBy(
                ['user_id' => $this->getUser()->getId(), 'negotiationCategory' => 4, 'forVerification' => false, 'acceptedOffer' => false, 'competitorLink' => null],
                ['id'=>'DESC']
            );

        if($negotiation == null){
            throw new AccessDeniedException('Unable to access this page!');
        }

        if(($negotiation->getUserId()->getId() != $this->getUser()->getId()) || ($negotiation->getId() != $lastNegotiation->getId())) {
            throw new AccessDeniedException('Unable to access this page!');
        }


        $form = $this->createForm(EnterLinkFormType::class, $negotiation);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $negotiation->setForVerification(true);
            $em->persist($negotiation);
            $em->flush();
            $this->addFlash('success', 'Twój link zostanie wkrótce rozpatrzony');
            return $this->redirectToRoute('product_show', ['id'=>$negotiation->getProductId()->getId()]);
        }


        return $this->render('enter_link/index.html.twig', [
            'negotiation' => $form->createView()
        ]);
    }
}
