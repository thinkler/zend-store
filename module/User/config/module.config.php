<?php

namespace User;

return array(
    'doctrine' => array(
        'driver' => array(
            // overriding zfc-user-doctrine-orm's config
            'zfcuser_entity' => array(
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'paths' => __DIR__ . '/../src/' . __NAMESPACE__ . '/Entity'
            ),

            'orm_default' => array(
                'drivers' => array(
                    __NAMESPACE__ => 'zfcuser_entity',
                ),
            ),
        ),
    ),

    'zfcuser' => array(
        // telling ZfcUser to use our own class
        'user_entity_class' => __NAMESPACE__ . '\Entity\User',
        // telling ZfcUserDoctrineORM to skip the entities it defines
        'enable_default_entities' => false,
    ),

    'bjyauthorize' => array(

        'identity_provider' => 'BjyAuthorize\Provider\Identity\AuthenticationIdentityProvider',

        'role_providers'        => array(
            // using an object repository (entity repository) to load all roles into our ACL
            'BjyAuthorize\Provider\Role\ObjectRepositoryProvider' => array(
                'object_manager'    => 'doctrine.entitymanager.orm_default',
                'role_entity_class' => 'User\Entity\Role',
             ),
        ),
    ),

    'view_manager' => array(
        'template_path_stack' => array(
            'zfc-user' => __DIR__ . '/../view'
        )
    )
);
