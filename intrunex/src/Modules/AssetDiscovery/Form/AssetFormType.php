<?php

namespace App\Modules\AssetDiscovery\Form;

use App\Modules\AssetDiscovery\Entity\Asset;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AssetFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, ['label' => 'Asset Name'])
            ->add('ipAddress', TextType::class, ['label' => 'IP Address', 'required' => false])
            ->add('url', TextType::class, ['label' => 'URL', 'required' => false])
            ->add('domain', TextType::class, ['label' => 'Domain', 'required' => false])
            ->add('type', ChoiceType::class, [
                'choices' => [
                    'Server' => 'server',
                    'Router' => 'router',
                    'Switch' => 'switch',
                    'Firewall' => 'firewall',
                    'Other' => 'other',
                ],
                'label' => 'Asset Type',
            ])
            ->add('status', ChoiceType::class, [
                'choices' => [
                    'Active' => 'active',
                    'Inactive' => 'inactive',
                    'Unknown' => 'unknown',
                ],
                'label' => 'Status',
            ])
            ->add('description', TextType::class, [
                'label' => 'Description',
                'required' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Asset::class,
        ]);
    }
}




