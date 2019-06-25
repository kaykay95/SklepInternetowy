<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\EditProductType;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class EditProductController extends AbstractController
{
    /**
     * @Route("/editproduct/{id}", name="edit_product")
     */
    public function index(Product $product, Request $request, EntityManagerInterface $em)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'Unable to access this page!');

        $form = $this->createForm(EditProductType::class, $product);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($product);
            $em->flush();
            $this->addFlash('success', 'Produkt został zaktualizowany.');
            return $this->redirectToRoute('product_management');
        }


        return $this->render('edit_product/index.html.twig', [
            'product' => $form->createView()
        ]);
    }
}
