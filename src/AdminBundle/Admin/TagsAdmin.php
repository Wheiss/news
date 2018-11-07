<?php
/**
 * Created by https://github.com/Wheiss
 * Date: 05.11.2018
 * Time: 11:36
 */

namespace AdminBundle\Admin;


use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class TagsAdmin extends AbstractAdmin
{
    protected function configureFormFields(FormMapper $form)
    {
        $form->add('name', TextType::class);
    }

    protected function configureDatagridFilters(DatagridMapper $filter)
    {
        $filter->add('name');
    }

    protected function configureListFields(ListMapper $list)
    {
        $list->addIdentifier('name');
    }

}