<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use App\Entity\Product;
use App\Entity\Negotiation;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
class ProductController extends AbstractController
{
    /**
     * @Route("/product/{id}", name="product_show")
     */
    public function show(Request $request, $id)
    {
        $product = $this->getDoctrine()
        ->getRepository(Product::class)
        ->find($id);

        if (!$product) {
            throw $this->createNotFoundException(
                'No product found for id ' . $id
            );
        }
        $image = base64_encode(stream_get_contents($product->getPicture()));

        if ($this->isGranted('IS_AUTHENTICATED_FULLY')){
            $user_id = $this->getUser();
            $date = new \DateTime();
            $oldNegotiation = $this->getDoctrine()->getRepository(Negotiation::class)->findOneBy(
                ['user_id' => $this->getUser()->getId(), 'product_id' => $product->getId(), 'transactionId' => 0],
                ['id'=>'DESC']);
            if ($oldNegotiation != null) {
                $oldNegotiationId = $oldNegotiation->getId();
                $previousPrice = $oldNegotiation->getFinalPrice();
                $forVerification = $oldNegotiation->getForVerification();
            }
            else{
                $oldNegotiationId = 0;
                $previousPrice = $product->getPrice();
                $forVerification = false;
            }
        }

        else{
            $date = new \DateTime();
            $previousPrice = $product->getPrice();
            $user_id = $product_id = $oldNegotiation = $oldNegotiationId = 0;
            $forVerification = false;
        }

        $negotiation = new Negotiation($user_id, $product, $date, $previousPrice);
        $form = $this->createFormBuilder($negotiation)
            ->add('quantity',null, array(
                'attr' => ['min' => 1]))
            ->add('description')
            ->add('desiredDiscount')
            ->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($negotiation);
            $entityManager->flush();
            return $this->redirectToRoute('process_negotiation', ['id' => $negotiation->getId()] );
        }

        return $this->render('product/index.html.twig', [
            'product' => $product,
            'image' => $image,
            'negotiation' => $form->createView(),
            'oldNegotiation' => $oldNegotiationId,
            'forVerification' => $forVerification,
            'price' => $previousPrice,
        ]);
    }

}