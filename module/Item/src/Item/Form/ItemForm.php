<?php

namespace Item\Form;

use Zend\Form\Form;
use Zend\Form\Element;
use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;

class ItemForm extends Form
{
    public function __construct($name = null)
    {
        parent::__construct('item');
        $this->setInputFilter(new \Item\Form\ItemInputFilter());

        $this->add(array(
            'name' => 'id',
            'hidden' => 'true'
        ));
        $this->add(array(
            'name' => 'title',
            'type' => 'text',
            'options' => array(
                'label' => 'Title',
            ),
            'attributes' => array(
                'class' => 'form-control'
            )
        ));
        $this->add(array(
            'name' => 'price',
            'type' => 'text',
            'options' => array(
                'label' => 'Price',
            ),
            'attributes' => array(
                'class' => 'form-control'
            )
        ));
        $this->add(array(
            'name' => 'description',
            'type' => 'textarea',
            'options' => array(
                'label' => 'Description',
            ),
            'attributes' => array(
                'class' => 'form-control'
            )
        ));
        $this->add(array(
            'name' => 'submit',
            'type' => 'submit',
            'attributes' => array(
                'value' => 'Save',
                'id' => 'submitbutton',
            ),
            'attributes' => array(
                'class' => 'btn btn-success'
            )
        ));
        // $this->add(array(
        //     'name' => 'image',
        //     'options' => array(
        //         'label' => "Image"
        //     ),
        //     'attributes' => array(
        //         'type' => 'file',
        //         'class' => 'form-control'
        //     ),
        // ));
    }
}
