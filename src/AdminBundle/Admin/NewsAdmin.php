<?php
/**
 * Created by https://github.com/Wheiss
 * Date: 20.10.2018
 * Time: 14:01
 */

namespace AdminBundle\Admin;


use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Form\Type\AdminType;
use Sonata\CoreBundle\Validator\ErrorElement;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class NewsAdmin extends AbstractAdmin
{

    protected function configureFormFields(FormMapper $form)
    {
        $form
            ->add('name', TextType::class)
            ->add('date', DateTimeType::class)
            ->add('text', TextareaType::class)
            ->add('image', AdminType::class, [
                'delete' => false,
            ]);
    }

    protected function configureDatagridFilters(DatagridMapper $filter)
    {
        $filter->add('name');
    }

    protected function configureListFields(ListMapper $list)
    {
        $list->addIdentifier('name');
        $list->add('date');
    }

    public function preUpdate($newsItem)
    {
        $this->manageEmbeddedImageAdmins($newsItem);
    }

    public function prePersist($newsItem)
    {
        $this->manageEmbeddedImageAdmins($newsItem);
    }

    private function manageEmbeddedImageAdmins($newsItem)
    {
        $image = $newsItem->getImage();

        if ($image) {
            if ($image->getUploadedFile()) {
                $image->refreshUpdated();
            } else {
                $newsItem->setImage(null);
            }
        }
    }

}