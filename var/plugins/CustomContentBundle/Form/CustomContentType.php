<?php

/*
 * This file is part of the "Custom Content Bundle" for Kimai.
 * All rights reserved by Kevin Papst (www.kevinpapst.de).
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace KimaiPlugin\CustomContentBundle\Form;

use KimaiPlugin\CustomContentBundle\Entity\CustomContent;
use KimaiPlugin\CustomContentBundle\Validator\Constraints\CustomContent as CustomContentConstraint;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CustomContentType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('stylesheet', TextareaType::class, [
                'label' => 'custom_content.tab_css',
                'required' => false,
                'attr' => [
                    'rows' => '15',
                ],
                'help' => 'custom_content.subtitle_stylesheet',
            ])
            ->add('javascript', TextareaType::class, [
                'label' => 'custom_content.tab_javascript',
                'required' => false,
                'attr' => [
                    'rows' => '15',
                ],
                'help' => 'custom_content.subtitle_javascript',
            ])
            ->add('alert', TextareaType::class, [
                'label' => 'custom_content.tab_alert',
                'required' => false,
                'attr' => [
                    'rows' => '3',
                ],
                'help' => 'custom_content.subtitle_alert',
            ])
            ->add('newsTitle', TextType::class, [
                'required' => false,
                'label' => 'title',
                'help' => 'custom_content.subtitle_newstitle',
            ])
            ->add('news', TextareaType::class, [
                'required' => false,
                'attr' => [
                    'rows' => '3',
                ],
                'help' => 'custom_content.subtitle_news',
            ])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CustomContent::class,
            'csrf_protection' => true,
            'csrf_field_name' => '_token',
            'csrf_token_id' => 'edit_custom_content',
            'method' => 'POST',
            'constraints' => [
                new CustomContentConstraint()
            ]
        ]);
    }
}
