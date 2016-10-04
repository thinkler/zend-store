<?php

namespace Item\Form;

use Zend\Form\Form;
use Zend\InputFilter\InputFilter;
// use Zend\InputFilter\FileInput;

class ItemInputFilter extends InputFilter
{
    public function __construct()
    {
        $this->add(array(
            'name' => 'title',
            'required' => true,
            'filters' => array(
                array('name' => 'StripTags'),
                array('name' => 'StringTrim'),
            ),
            'validators' => array(
                array(
                    'name' => 'StringLength',
                    'options' => array(
                        'min' => 3,
                        'max' => 20,
                    ),
                ),
            ),
        ));

        $this->add(array(
            'name' => 'description',
            'required' => true,
            'filters' => array(
                array('name' => 'StripTags'),
                array('name' => 'StringTrim'),
            ),
            'validators' => array(
                array(
                    'name' => 'StringLength',
                    'options' => array(
                        'min' => 10,
                        'max' => 100,
                    ),
                ),
            ),
        ));

        $this->add(array(
            'name' => 'price',
            'required' => true
        ));

        // $this->add(array(
        //     'name'     => 'image',
        //     'required' => true,
        //     'filters'  => array(
        //         array('name' => 'Zend\Filter\File\RenameUpload',
        //             'options'=>array(
        //                 'target'    => "./data/images/image.png"
        //             )
        //         ),
        //     ),
        // ));
    }
}
