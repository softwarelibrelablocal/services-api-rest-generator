<?php

if( function_exists('acf_add_local_field_group') ):

    acf_add_local_field_group(array (
        'key' => 'group_583ebc5bcec7b',
        'title' => 'MGDBQ2JSON Usuarios',
        'fields' => array (
            array (
                'key' => 'field_583ebc5c26b6f',
                'label' => 'API Key del Usuario',
                'name' => 'mgdbq2josn_uhashkey',
                'type' => 'acf-random-string',
                'instructions' => 'El usuario deberá especificar la API Key en el parémetro "u" de cada consulta que realice con el plugin MGDBQ2JSON.',
                'required' => 1,
                'conditional_logic' => 0,
                'wrapper' => array (
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'length' => 12,
                'alphanumeric' => array (
                    0 => '1',
                ),
            ),
            array (
                'key' => 'field_584fde6344e75',
                'label' => 'Estado',
                'name' => 'mgdbq2josn_user_state',
                'type' => 'select',
                'instructions' => 'Si el usuario está bloqueado no podrá utilizar los servicios a los que está asignado.',
                'required' => 1,
                'conditional_logic' => 0,
                'wrapper' => array (
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'user_roles' => array (
                    0 => 'administrator',
                ),
                'choices' => array (
                    'activo' => 'activo',
                    'bloqueado' => 'bloqueado',
                ),
                'default_value' => array (
                    0 => 'activo',
                ),
                'allow_null' => 0,
                'multiple' => 0,
                'ui' => 0,
                'ajax' => 0,
                'return_format' => 'value',
                'placeholder' => '',
            ),
        ),
        'location' => array (
            array (
                array (
                    'param' => 'user_form',
                    'operator' => '==',
                    'value' => 'edit',
                ),
            ),
        ),
        'menu_order' => 0,
        'position' => 'normal',
        'style' => 'default',
        'label_placement' => 'top',
        'instruction_placement' => 'label',
        'hide_on_screen' => '',
        'active' => 1,
        'description' => '',
    ));

endif;