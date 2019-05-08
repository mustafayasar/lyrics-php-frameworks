<?php

namespace App\Admin;

use App\Entity\Singer;
use App\Repository\SingerRepository;
use App\Utils\AdminHelper;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\Form\Validator\ErrorElement;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

final class SingerAdmin extends AbstractAdmin
{
    private $repository;

    public function __construct($code, $class, $baseControllerName, SingerRepository $repository) {
        parent::__construct($code, $class, $baseControllerName);

        $this->repository = $repository;
    }

    public function validate(ErrorElement $errorElement, $object)
    {
        parent::validate($errorElement, $object);

        $object->setSlug($this->repository->createSlug($object));

    }

    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper->add('name', TextType::class);

        if ($this->isCurrentRoute('edit')) {
            $formMapper->add('slug', TextType::class);
            $formMapper->add('hit', IntegerType::class, ['disabled' => 'disabled']);
        }

        $formMapper->add('status', ChoiceType::class, ['choices' => array_flip(Singer::$statuses)]);
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper->add('id', 'doctrine_orm_number', [], IntegerType::class);
        $datagridMapper->add('name');
        $datagridMapper->add('status', 'doctrine_orm_choice', [],ChoiceType::class, ['choices' => array_flip(Singer::$statuses)]);
//        $datagridMapper->add('status', ChoiceType::class, ['choices' => array_flip(Singer::$statuses)]);
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper->addIdentifier('id');
        $listMapper->addIdentifier('name');
        $listMapper->add('slug');
        $listMapper->add('hit');
        $listMapper->add('status', 'choice', ['choices' => Singer::$statuses]);
    }
}