<?php

namespace AppBundle\Form;

use AppBundle\Service\EntityListingService;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EntitySelectType extends AbstractType
{
    private $lister;
    private $namespace;

    public function __construct(EntityListingService $lister, $namespace)
    {
        $this->lister = $lister;

        $this->namespace = $namespace;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefault('choices', $this->lister->listEntities($this->namespace));
    }

    public function getParent()
    {
        return ChoiceType::class;
    }

    public function getName()
    {
        return 'entity_select';
    }
}