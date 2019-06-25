<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use App\Entity\Product;
use App\Form\NewProductType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class NewProductController extends AbstractController
{
    /**
     * @Route("/newproduct", name="new_product")
     */
    public function index(Request $request)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'Unable to access this page!');

        $product = new Product();
        $form = $this->createForm(NewProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($product);
            $entityManager->flush();
            return $this->redirectToRoute('product_management');
        }
        return $this->render('new_product/index.html.twig', [
            'product' => $form->createView()
        ]);
    }
}
