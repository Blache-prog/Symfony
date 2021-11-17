<?php

namespace App\Form;

use App\Entity\Transaction;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;
use App\Entity\Account;

class TransactionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $owner = $options['owner'];
        $builder
            ->add('date')
            ->add('amount')
            ->add('label')
            ->add('debitAccount', EntityType::class,[
                'class' => Account::class,
                'choice_label' => 'iban',
                'query_builder' => function(EntityRepository $er) use ($owner) {
                    return $er->createQueryBuilder('a')
                    ->where('a.owner = :owner')
                    ->setParameter(':owner', $owner);
                }
            ])
            ->add('creditAccount', EntityType::class,[
                'class' => Account::class,
                'choice_label' => 'iban',
                'query_builder' => function(EntityRepository $er) use ($owner) {
                    return $er->createQueryBuilder('a')
                    ->join('a.senders', 'o')
                    ->where('o.id = :owner')
                    ->setParameter(':owner', $owner);
                }
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Transaction::class,
            'owner' => null,
        ]);
    }
}
