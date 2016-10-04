<?php

return array(
    'zfctwig' => array(
        'extensions' => array(
            'debug' => 'Twig_Extension_Debug'
        ),
        'environment_options' => array('debug' => true)
    ),

    'view_manager' => array(
        'strategies' => array(
            'ZfcTwigViewStrategy'
        )
    )
);
