<?php

namespace App\Controller;

use App\Entity\Owner;
use App\Entity\Transaction;
use App\Form\TransactionType;
use App\Repository\TransactionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/transaction")
 */
class TransactionController extends AbstractController
{
    /**
     * @Route("/new/owner/{id}", name="transaction_new_owner", methods={"GET","POST"})
     */
    public function new(Request $request, Owner $owner): Response
    {
        $transaction = new Transaction();
        $form = $this->createForm(TransactionType::class, $transaction, [
            'owner' => $owner
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();

            // debit account debitAccount: debitAccount->balance - transaction->amount
            $debitAccount = $transaction->getDebitAccount();
            $debitAccount->setBalance($debitAccount->getBalance() - $transaction->getAmount());

            //credit account creditAccount: creditAccount->balance + transaction->amount
            $creditAccount = $transaction->getCreditAccount();
            $creditAccount->setBalance($creditAccount->getBalance() + $transaction->getAmount());

            $entityManager->persist($transaction);
            $entityManager->flush();

            return $this->redirectToRoute('owner_show', ['id' => $owner->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('transaction/new.html.twig', [
            'transaction' => $transaction,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="transaction_show", methods={"GET"})
     */
    public function show(Transaction $transaction): Response
    {
        return $this->render('transaction/show.html.twig', [
            'transaction' => $transaction,
        ]);
    }
}
