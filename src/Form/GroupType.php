<?php

namespace App\Form;

use App\Controller\GroupController;
use App\Entity\Group;
use App\Entity\User;
use App\Entity\Wishlist;
use App\Repository\GroupRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;

class GroupType extends AbstractType
{
//    private $security;
//
//    public function __construct(Security $security)
//    {
//        $this->security = $security;
//    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
//        dd($options['user']);
//        /** @var User $user */
//        $user = $this->security->getUser()->get;

        $builder
            ->setMethod('GET')
            ->add('name', TextType::class, [
                'label' => 'Nom du groupe'
            ])
            ->add('users', null, [
                'label' => 'Membres',
                'choice_label' => 'name',
                'expanded' => true,
                'multiple' => true,
                'attr' => ['class' => 'users py-3']
            ])
            ->add('wishlists', EntityType::class, [
                'label' => 'Listes',
                'class' => Wishlist::class,
                'choice_label' => 'name',
                'expanded' => true,
                'multiple' => true,
                'attr' => ['class' => 'users py-3'],
                'query_builder' => function (EntityRepository $er) use ($options) {
                return $er->createQueryBuilder('w')
                    ->where('w.user = :user')
                    ->setParameter('user', $options['user']);
                },
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Group::class,
            'user' => null,
        ]);
    }
}
