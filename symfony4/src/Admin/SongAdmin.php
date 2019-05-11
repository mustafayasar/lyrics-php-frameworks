<?php

//declare(strict_types=1);

namespace App\Admin;

use App\Entity\Singer;
use App\Entity\Song;
use App\Repository\SongRepository;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\ProxyQueryInterface;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\Form\Validator\ErrorElement;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Sonata\AdminBundle\Show\ShowMapper;
use Symfony\Component\Form\Extension\Core\Type\TextType;

final class SongAdmin extends AbstractAdmin
{
    private $repository;

    public function __construct($code, $class, $baseControllerName, SongRepository $repository) {
        parent::__construct($code, $class, $baseControllerName);

        $this->repository = $repository;
    }

    public function validate(ErrorElement $errorElement, $object)
    {
        parent::validate($errorElement, $object);

        $object->setSlug($this->repository->createSlug($object));
    }

    protected function configureFormFields(FormMapper $formMapper): void
    {
        $formMapper
            ->add('singer',EntityType::class,[
                'class' => Singer::class,
                'choice_label' => 'name',
                'choice_value' => 'id',
                'label' => 'Singer',
            ])
            ->add('title');

        if ($this->isCurrentRoute('edit')) {
            $formMapper->add('slug');
        }

        $formMapper->add('lyrics', TextareaType::class, ['attr' => ['style' => 'height: 400px;']]);

        if ($this->isCurrentRoute('edit')) {
            $formMapper->add('hit', IntegerType::class, ['disabled' => 'disabled']);
        }

        $formMapper->add('status', ChoiceType::class, ['choices' => array_flip(Song::$statuses)]);
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper): void
    {
        $datagridMapper
            ->add('id')
            ->add('singer_id')
            ->add('title')
            ->add('status')
            ;
    }

    protected function configureListFields(ListMapper $listMapper): void
    {
        $listMapper
            ->addIdentifier('id')
            ->add('singer.name')
            ->add('title')
            ->add('slug')
            ->add('hit')
            ->add('status', 'choice', ['choices' => Song::$statuses])
            ->add('created_at',  'date', ['format' => 'Y-m-d H:i:s'])
            ->add('_action', null, [
                'actions' => [
                    'show' => [],
                    'edit' => [],
                    'delete' => [],
                ],
            ]);
    }

    protected function configureShowFields(ShowMapper $showMapper): void
    {
        $showMapper
            ->add('id')
            ->add('singer_id')
            ->add('singer.name')
            ->add('title')
            ->add('slug')
            ->add('lyrics')
            ->add('hit')
            ->add('status', 'choice', ['choices' => Song::$statuses])
            ->add('created_at',  'date', ['format' => 'Y-m-d H:i:s'])
            ;
    }
}
