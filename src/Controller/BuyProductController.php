<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Product;
use App\Entity\Negotiation;
use App\Entity\Transaction;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class BuyProductController extends AbstractController
{
    /**
     * @Route("/buyproduct/{id}", name="buy_product")
     */
    public function index($id, EntityManagerInterface $em)
    {
        $transaction = $this->getDoctrine()
            ->getRepository(Transaction::class)
            ->find($id);

        $lastTransaction = $this->getDoctrine()
            ->getRepository(Transaction::class)
            ->findOneBy(
                ['user' => $this->getUser(), 'product' => $transaction->getProduct()],
                ['id'=>'DESC']
            );

        if($transaction == null){
            throw new AccessDeniedException('Unable to access this page!');
        }

        if(($transaction->getUser()->getId() != $this->getUser()->getId()) || ($transaction->getId() != $lastTransaction->getId())){
            throw new AccessDeniedException('Unable to access this page!');
        }


        $userId = $this->getUser()->getId();
        $productId = $transaction->getProduct()->getId();
        $negotiationCount = $this->getDoctrine()->getRepository(Negotiation::class)->findNegotiationCount($userId, $productId, $transaction->getId());

        $image = base64_encode(stream_get_contents($transaction->getProduct()->getPicture()));

        return $this->render('buy_product/index.html.twig', [
            'transaction' => $transaction,
            'negotiationCount' => $negotiationCount,
            'image' => $image,
            'id' => $id
        ]);
    }


    /**
     * @Route("/processbuyproduct/{id}", name="process_buy_product")
     */
    public function processBuyProduct($id, EntityManagerInterface $em)
    {
        $negotiation = $this->getDoctrine()
            ->getRepository(Negotiation::class)
            ->find($id);


        $transaction = new Transaction ();
        $transaction->setUser($this->getUser());
        $transaction->setProduct($negotiation->getProductId());
        $transaction->setLastNegotiation($negotiation->getId());
        $transaction->setPrice($negotiation->getFinalPrice());
        $transaction->setDiscount($negotiation->getProductId()->getPrice()-$negotiation->getFinalPrice());

        $negotiations = $this->getDoctrine()
            ->getRepository(Negotiation::class)
            ->findBy(['user_id' => $this->getUser()->getId(), 'product_id' => $negotiation->getProductId()->getId(), 'transactionId' => 0 ]);

        $bigQuantityDiscount = false;
        $quantity = 1;

        foreach ($negotiations as $entry) {
            if ($entry->getQuantity() > 1) {
                $bigQuantityDiscount = true;
                $quantity = $entry->getQuantity();
            }
        }

        $transaction->setQuantity($quantity);
        $transaction->setAmount($negotiation->getFinalPrice() * $quantity);


        $em->persist($transaction);
        $em->flush();

        $negotiations = $this->getDoctrine()
            ->getRepository(Negotiation::class)
            ->findBy(['user_id' => $this->getUser()->getId(), 'product_id' => $negotiation->getProductId()->getId(), 'transactionId' => 0 ]);

        foreach ($negotiations as $entry){
            $entry->setTransactionId($transaction->getId());
            $em->persist($entry);
            $em->flush();

        }


        return $this->redirectToRoute('buy_product', ['id'=>$transaction->getId()]);
    }

    /**
     * @Route("/processbuyproduct/product/{id}", name="process_buy_product_product")
     */
    public function processBuyProductProduct($id, EntityManagerInterface $em)
    {
        $negotiation = null;

        $product = $this->getDoctrine()
            ->getRepository(Product::class)
            ->find($id);

        $image = base64_encode(stream_get_contents($product->getPicture()));

        $transaction = new Transaction ();
        $transaction->setUser($this->getUser());
        $transaction->setProduct($product);
        $transaction->setLastNegotiation(0);
        $transaction->setPrice($product->getPrice());
        $transaction->setDiscount(0);
        $transaction->setQuantity(1);
        $transaction->setAmount($product->getPrice());
        $em->persist($transaction);
        $em->flush();

        return $this->redirectToRoute('buy_product', ['id'=>$transaction->getId()]);
    }

    /**
     * @Route("/vote/{id}/{option}", name="vote")
     */
    public function questionnaireVote($id, $option,  EntityManagerInterface $em)
    {
        $transaction = $this->getDoctrine()
            ->getRepository(Transaction::class)
            ->find($id);
        $transaction->setQuestionnaire($option);
        $em->persist($transaction);
        $em->flush();
        $this->addFlash('success', 'Dziękuję za udział w ankiecie! :)');
        return $this->redirectToRoute('buy_product', ['id'=>$id]);
    }

}
