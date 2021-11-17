<?php

namespace App\Entity;

use App\Repository\TransactionRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=TransactionRepository::class)
 */
class Transaction
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $date;

    /**
     * @ORM\Column(type="integer")
     * @Assert\Expression(
     *     "this.getDebitAccount().getBalance() - value > this.getDebitAccount().getMinimumBalance()",
     *     message="Le solde du debiteur n'est pas suffisant"
     * )
     * @Assert\Positive()
     */
    private $amount;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $label;

    /**
     * @ORM\ManyToOne(targetEntity=Account::class, inversedBy="debitTransactions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $debitAccount;

    /**
     * @ORM\ManyToOne(targetEntity=Account::class, inversedBy="creditTransactions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $creditAccount;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(?\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getAmount(): ?int
    {
        return $this->amount;
    }

    public function setAmount(int $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(string $label): self
    {
        $this->label = $label;

        return $this;
    }

    public function getDebitAccount(): ?Account
    {
        return $this->debitAccount;
    }

    public function setDebitAccount(?Account $debitAccount): self
    {
        $this->debitAccount = $debitAccount;

        return $this;
    }

    public function getCreditAccount(): ?Account
    {
        return $this->creditAccount;
    }

    public function setCreditAccount(?Account $creditAccount): self
    {
        $this->creditAccount = $creditAccount;

        return $this;
    }
}
