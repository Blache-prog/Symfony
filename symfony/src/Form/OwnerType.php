<?php

namespace App\Form;

use App\Entity\Owner;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\Account;
use Doctrine\ORM\EntityRepository;

class OwnerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $owner = $builder->getData();
        $builder
            ->add('firstname')
            ->add('lastname')
            ->add('birthday', DateType::class, [
                'years' => range(date('Y') - 18, date('Y') - 80),
            ])
            ->add('email')
            ->add('phoneNumber')
            ->add('beneficiaries', EntityType::class,[
                'class' => Account::class,
                'choice_label' => 'iban',
                'multiple' => true,
                'required' => false,
                'query_builder' => function (EntityRepository $er) use ($owner) {
                    if ($owner->getId()) {
                        return $er->findAccountsFromDifferentOwner($owner);
                    }
                    return $er->createQueryBuilder('a');
                },
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Owner::class,
        ]);
    }
}
