<?php

namespace App\Controller;

use App\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;


class ProductManagementController extends AbstractController
{
    /**
     * @Route("/productmanagement", name="product_management")
     */
    public function index()
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'Unable to access this page!');
        $products = $this->getDoctrine()
            ->getRepository(Product::class)->findAll();

        return $this->render('product_management/index.html.twig', [
            'products' => $products
        ]);
    }

    /**
     * @Route("/deleteproduct/{id}", name="delete_product")
     */
    public function executeDelete($id){

        $product = $this->getDoctrine()
            ->getRepository(Product::class)
            ->find($id);

        $em = $this->getDoctrine()->getManager();

        $em->remove($product);
        $em->flush();
        return $this->redirectToRoute('product_management');
    }
}
