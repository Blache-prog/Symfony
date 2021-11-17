<?php

namespace App\Entity;

use App\Repository\AccountRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AccountRepository::class)
 */
class Account
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $iban;

    /**
     * @ORM\Column(type="integer")
     */
    private $balance;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $type;

    /**
     * @ORM\ManyToOne(targetEntity=Owner::class, inversedBy="accounts")
     * @ORM\JoinColumn(nullable=false)
     */
    private $owner;

    /**
     * @ORM\OneToMany(targetEntity=Transaction::class, mappedBy="debitAccount", orphanRemoval=true)
     */
    private $debitTransactions;

    /**
     * @ORM\OneToMany(targetEntity=Transaction::class, mappedBy="creditAccount", orphanRemoval=true)
     */
    private $creditTransactions;

    /**
     * @ORM\Column(type="integer")
     */
    private $minimumBalance;

    /**
     * @ORM\ManyToMany(targetEntity=Owner::class, mappedBy="beneficiaries")
     * @ORM\JoinTable(name="owner_account")
     */
    private $senders;

    public function __construct()
    {
        $this->debitTransactions = new ArrayCollection();
        $this->creditTransactions = new ArrayCollection();
        $this->senders = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIban(): ?string
    {
        return $this->iban;
    }

    public function setIban(string $iban): self
    {
        $this->iban = $iban;

        return $this;
    }

    public function getBalance(): ?int
    {
        return $this->balance;
    }

    public function setBalance(int $balance): self
    {
        $this->balance = $balance;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getOwner(): ?Owner
    {
        return $this->owner;
    }

    public function setOwner(?Owner $owner): self
    {
        $this->owner = $owner;

        return $this;
    }

    /**
     * @return Collection|Transaction[]
     */
    public function getDebitTransactions(): Collection
    {
        return $this->debitTransactions;
    }

    public function addDebitTransaction(Transaction $debitTransaction): self
    {
        if (!$this->debitTransactions->contains($debitTransaction)) {
            $this->debitTransactions[] = $debitTransaction;
            $debitTransaction->setDebitAccount($this);
        }

        return $this;
    }

    public function removeDebitTransaction(Transaction $debitTransaction): self
    {
        if ($this->debitTransactions->removeElement($debitTransaction)) {
            // set the owning side to null (unless already changed)
            if ($debitTransaction->getDebitAccount() === $this) {
                $debitTransaction->setDebitAccount(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Transaction[]
     */
    public function getCreditTransactions(): Collection
    {
        return $this->creditTransactions;
    }

    public function addCreditTransaction(Transaction $creditTransaction): self
    {
        if (!$this->creditTransactions->contains($creditTransaction)) {
            $this->creditTransactions[] = $creditTransaction;
            $creditTransaction->setCreditAccount($this);
        }

        return $this;
    }

    public function removeCreditTransaction(Transaction $creditTransaction): self
    {
        if ($this->creditTransactions->removeElement($creditTransaction)) {
            // set the owning side to null (unless already changed)
            if ($creditTransaction->getCreditAccount() === $this) {
                $creditTransaction->setCreditAccount(null);
            }
        }

        return $this;
    }

    public function getMinimumBalance(): ?int
    {
        return $this->minimumBalance;
    }

    public function setMinimumBalance(int $minimumBalance): self
    {
        $this->minimumBalance = $minimumBalance;

        return $this;
    }

    /**
     * @return Collection|Account[]
     */
    public function getSenders(): Collection
    {
        return $this->senders;
    }

    public function addSender(Owner $sender): self
    {
        if (!$this->senders->contains($sender)) {
            $this->senders[] = $sender;
        }

        return $this;
    }

    public function removeSender(Owner $sender): self
    {
        $this->senders->removeElement($sender);

        return $this;
    }
}
