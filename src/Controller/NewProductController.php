<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\NewProductType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class NewProductController extends AbstractController
{
    /**
     * @Route("/newproduct", name="new_product")
     */
    public function index()
    {
        $product = new Product();
        $form = $this->createForm(NewProductType::class, $product);

        return $this->render('new_product/index.html.twig', [
            'product' => $form->createView()
        ]);
    }
}
