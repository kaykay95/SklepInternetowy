<?php

namespace App\Controller;

use App\Entity\Negotiation;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;


class NegotiationReviewController extends AbstractController
{
    /**
     * @Route("/negotiationreview", name="negotiation_review")
     */
    public function index()
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'Unable to access this page!');

        $negotiations = $this->getDoctrine()
            ->getRepository(Negotiation::class)
            ->findAll();

        return $this->render('negotiation_review/index.html.twig', [
            'negotiations' => $negotiations,
        ]);
    }

    /**
     * @Route("/acceptnegotiation/{id}", name="accept_negotiation")
     */

    public function acceptNegotiation ($id, EntityManagerInterface $em)
    {
        $negotiation = $this->getDoctrine()
            ->getRepository(Negotiation::class)
            ->find($id);

        $negotiation->setForVerification(false);
        $negotiation->setAcceptedOffer(true);
        $negotiation->setFinalPrice($negotiation->getPreviousPrice() - $negotiation->getDesiredDiscount());
        $em->persist($negotiation);
        $em->flush();

        $this->addFlash('success', 'Negocjacja została zweryfikowana.');
        return $this->redirectToRoute('negotiation_review');

    }
    /**
     * @Route("/rejectnegotiation/{id}", name="accept_negotiation")
     */

    public function rejectNegotiation ($id, EntityManagerInterface $em)
    {
        $negotiation = $this->getDoctrine()
            ->getRepository(Negotiation::class)
            ->find($id);

        $negotiation->setForVerification(false);
        $negotiation->setAcceptedOffer(false);
        $negotiation->setFinalPrice($negotiation->getPreviousPrice());
        $em->persist($negotiation);
        $em->flush();

        $this->addFlash('success', 'Negocjacja została odrzucona.');
        return $this->redirectToRoute('negotiation_review');

    }
}
