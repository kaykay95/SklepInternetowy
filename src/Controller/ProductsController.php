<?php

namespace App\Controller;

use App\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ProductsController extends AbstractController
{
    /**
     * @Route("/products", name="products")
     */
    public function index()
    {
        $products = $this->getDoctrine()
            ->getRepository(Product::class)->findAll();


        $images = array();
        foreach ($products as $key => $entity) {
            $images[$key] = base64_encode(stream_get_contents($entity->getPicture()));
        }
        return $this->render('products/index.html.twig', [
            'products' => $products,
            'images' => $images
        ]);
    }
}
