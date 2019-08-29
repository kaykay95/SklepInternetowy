<?php

namespace App\Controller;

use App\Entity\Negotiation;
use App\Entity\NegotiationCategory;
use App\Entity\Product;
use App\Entity\Transaction;
use App\Entity\NegotiationExpression;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use NlpTools\Tokenizers\WhitespaceTokenizer;
use NlpTools\Similarity\CosineSimilarity;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class NegotiationSummaryController extends AbstractController
{
    /**
     * @Route("/negotiationsummary/{id}", name="negotiation_summary")
     */
    public function renderSite($id)
    {

        $negotiation = $this->getDoctrine()
            ->getRepository(Negotiation::class)
            ->find($id);

        $lastNegotiation = $this->getDoctrine()
            ->getRepository(Negotiation::class)
            ->findOneBy(
                ['user_id' => $this->getUser()->getId(), 'product_id' => $negotiation->getProductId()->getId()],
                ['id'=>'DESC']
    );

        if($negotiation == null){
            throw new AccessDeniedException('Unable to access this page!');
        }

        if(($negotiation->getUserId()->getId() != $this->getUser()->getId()) || ($negotiation->getId() != $lastNegotiation->getId())) {
            throw new AccessDeniedException('Unable to access this page!');
        }

        $product = $this->getDoctrine()
            ->getRepository(Product::class)
            ->find($negotiation->getProductId());

        if ($negotiation->getNegotiationCategory() != null){

            $category = $this->getDoctrine()
                ->getRepository(NegotiationCategory::class)
                ->find($negotiation->getNegotiationCategory());

            $category = $category->getName();
        }
        else{
            $category = "";
        }

        $image = base64_encode(stream_get_contents($product->getPicture()));

        list($header, $response) =
            $this->getResponse($negotiation->getCorrectDiscount(),$negotiation->getCorrectExpression(),  $negotiation->getCorrectExpressionbyCategory(), $negotiation->getCategoryNotUsed(), $negotiation->getAcceptedOffer(), $category, $product->getName());

        return $this->render('negotiation_summary/index.html.twig', [
            'negotiation' => $negotiation,
            'product' => $product,
            'image' => $image,
            'category' => $category,
            'response' => $response,
            'header' => $header
        ]);
    }

    /**
     * @Route("/processNegotiation/{id}", name="process_negotiation")
     */
    public function index($id, EntityManagerInterface $em)
    {
        $negotiation = $this->getDoctrine()
            ->getRepository(Negotiation::class)
            ->find($id);

        $product = $this->getDoctrine()
            ->getRepository(Product::class)
            ->find($negotiation->getProductId());

        $correctDiscount = $correctExpression = $correctByCategory = $categoryNotUsed = false;

        $correctDiscount = $this->checkIfDiscountIfCorrect($negotiation->getPreviousPrice(), $negotiation->getDesiredDiscount(), $product->getNegotiationRatio());


        if ($correctDiscount) {
            $negotiation->setCorrectDiscount($correctDiscount);

            list($correctExpression, $category) = $this->checkIfNegotiationExpressionIsCorrect($negotiation->getDescription());


            if($correctExpression) {
                $negotiation->setCorrectExpression($correctExpression);
                $negotiation->setNegotiationCategory($category);

                $correctByCategory = $this->checkIfNegotiationExpressionIsCorrectByCategory($category, $negotiation->getUserId(), $negotiation->getProductId(), $negotiation->getQuantity());
                if($correctByCategory) {
                    $negotiation->setCorrectExpressionbyCategory(true);

                     if ($this->getDoctrine()->getRepository(Negotiation::class)->findByUserProductAndCategory( $this->getUser()->getId(), $product -> getId(), $category) == null) {
                         $categoryNotUsed = true;
                         $negotiation->setCategoryNotUsed(true);
                         if ($category->getName() == "Konkurencja"){
                             $negotiation->setFinalPrice($negotiation->getPreviousPrice());
                             $em->persist($negotiation);
                             $em->flush();
                             return $this->redirectToRoute('enter_link', ['id'=> $negotiation->getId()]);
                         }
                         $negotiation->setFinalPrice($negotiation->getPreviousPrice() - $negotiation->getDesiredDiscount());
                         $negotiation->setAcceptedOffer(true);
                     }
                    else
                        $categoryNotUsed = false;

                }
            }
        }


        if (!($correctDiscount && $correctExpression && $correctByCategory && $categoryNotUsed))
            $negotiation->setFinalPrice($negotiation->getPreviousPrice());

        $em->persist($negotiation);
        $em->flush();

        return $this->redirectToRoute('negotiation_summary', ['id'=> $id]);
    }

    public function checkIfDiscountIfCorrect ($previousPrice, $desiredDiscount, $negotiationRatio) :bool
    {
        if($desiredDiscount < $previousPrice*($negotiationRatio/100))
            return true;
        else
            return false;

    }

    public function checkIfNegotiationExpressionIsCorrect(string $description) :array
    {
        $expressions = $this->getDoctrine()
            ->getRepository(NegotiationExpression::class)->findAll();

        $correctExpression = false;
        $category = "";
        $maxSimilarity = 0;
        foreach ($expressions as $expression) {
            $similarity = $this->calcNegotiationExpressionSimilarity($description, $expression->getExpression());
            if ($maxSimilarity < $similarity) {
                $maxSimilarity = $similarity;
                $category = $expression->getNegotiationCategory();
            }
        }
        if ($maxSimilarity >= 0.2) {
            $correctExpression = true;
        }
        return array($correctExpression, $category);
    }

    public function calcNegotiationExpressionSimilarity($s1, $s2) :float
    {
        $tok = new WhitespaceTokenizer();

        $setA = $tok->tokenize($s1);
        $setB = $tok->tokenize($s2);

        $cos = new CosineSimilarity();
        $similarity = $cos->similarity(
            $setA,
            $setB
        );
        return $similarity;
    }

    public function checkIfNegotiationExpressionIsCorrectByCategory($categoryId, $userID, $productId, $quantity):bool
    {
        $category = $this->getDoctrine()
            ->getRepository(NegotiationCategory::class)
            ->find($categoryId);
        $negotiationExpressionIsCorrectByCategory = false;
        if ($category->getName() == "Nowy klient") {
            $transactionCount = $this->getDoctrine()->getRepository(Transaction::class)->findFinishedTransactionCount($userID);
            if ($transactionCount <= 1)
                $negotiationExpressionIsCorrectByCategory = true;
        }
        elseif ($category->getName() == "Stały klient"){
            $transactionCount = $this->getDoctrine()->getRepository(Transaction::class)->findFinishedTransactionCount($userID);
            if ($transactionCount > 5)
                $negotiationExpressionIsCorrectByCategory = true;
        }
        elseif ($category->getName() == "Duża ilość towaru") {
            if ($quantity >=2)
                $negotiationExpressionIsCorrectByCategory = true;
        }
        elseif ($category->getName() == "Konkurencja") {
            $negotiationExpressionIsCorrectByCategory = true;
        }
        return $negotiationExpressionIsCorrectByCategory;
    }

    public function getResponse($correctDiscount,$correctExpression, $correctByCategory, $categoryNotUsed, $acceptedOffer, $category, $product):array
    {
        if ($acceptedOffer)
            $header = "Gratulacje!";
        else
            $header = "Przykro nam :(";
        if (!($correctDiscount))
            return array($header, "Nie jestśmy w stanie dać tak dużej zniżki na ten produkt. Może masz jakąś inną propozycję?");
        if(!($correctExpression))
            return array($header, "Nie akceptujemy takich uzasadnień. Chcesz spróbować jeszcze raz?");
        if(!($correctByCategory)) {
            if ($category == "Stały klient")
                return array($header, "Nie jesteś naszym stałym klientem. Czy są jeszcze inne powody, dla których chesz dostać zniżkę?");
            if ($category == "Nowy klient")
                return array($header, "Nie jesteś już naszym nowym klientem. Czy są jeszcze inne powody, dla których chesz dostać zniżkę?");
            if ($category == "Duża ilość towaru")
                return array($header, "To nie jest duża ilość towaru. Czy są jeszcze inne powody, dla których chesz dostać zniżkę?");
        }
        if(!($categoryNotUsed))
            return array($header,"Dostałeś już zniżkę typu " . $category . ". Czy są jeszcze inne powody, dla których chesz dostać zniżkę?");
        if((! $acceptedOffer) && ($category == "Konkurencja"))
            return array($header, "Weryfikacja linku zakończyła się niepowodzeniem. Chcesz zanegcjować jeszcze raz?");
        else
            return array($header,"Udało Ci się namówić nas do obniżki ceny produktu $product");

    }

}
