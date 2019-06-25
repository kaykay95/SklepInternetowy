<?php

namespace App\Controller;

use App\Entity\Transaction;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class TransactionReviewController extends AbstractController
{
    /**
     * @Route("/transactionreview", name="transaction_review")
     */
    public function index()
    {
        $transactions = $this->getDoctrine()
            ->getRepository(Transaction::class)
            ->findAll();

        return $this->render('transaction_review/index.html.twig', [
            'transactions' => $transactions
        ]);
    }
}
