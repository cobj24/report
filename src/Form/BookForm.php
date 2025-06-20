<?php

namespace App\Form;

use App\Entity\Book;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Formulär för hantering av Book-entiteten.
 */
class BookForm extends AbstractType
{
    /**
     * Bygger formuläret med fälten för Book-entiteten.
     *
     * @param FormBuilderInterface $builder formbyggaren
     * @param array                $options alternativ för formuläret
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')
            ->add('isbn')
            ->add('author')
            ->add('image')
        ;
    }

    /**
     * Konfigurerar standardalternativ för formuläret.
     *
     * @param OptionsResolver $resolver resolver för alternativen
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Book::class,
        ]);
    }
}
