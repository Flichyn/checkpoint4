<?php

namespace App\Form;

use App\Entity\Wish;
use App\Entity\Wishlist;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class WishlistType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->setMethod('GET')
            ->add('name', TextType::class, [
                'label' => 'Nom de la liste'
            ])
            ->add('wishes', EntityType::class, [
                'label' => 'Souhaits',
                'class' => Wish::class,
                'choice_label' => 'name',
                'expanded' => true,
                'multiple' => true,
                'attr' => ['class' => 'wishes py-3'],
                'query_builder' => function (EntityRepository $er) use ($options) {
                    return $er->createQueryBuilder('wi')
                        ->where('wi.user = :user')
                        ->setParameter('user', $options['user']);
                }
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Wishlist::class,
            'user' => null,
        ]);
    }
}
