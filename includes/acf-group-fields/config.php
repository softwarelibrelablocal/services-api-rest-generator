<?php
if( function_exists('acf_add_local_field_group') ):

    acf_add_local_field_group(array (
        'key' => 'group_58623d46b3150',
        'title' => 'Configuracion',
        'fields' => array (
            array (
                'key' => 'field_58623ed1101be',
                'label' => 'Número de intentos erróneos',
                'name' => 'mgdbq2json_nro_intentos',
                'type' => 'number',
                'instructions' => 'Indique el número de intentos antes de bloquear el usuario.',
                'required' => 1,
                'conditional_logic' => 0,
                'wrapper' => array (
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'user_roles' => array (
                    0 => 'all',
                ),
                'default_value' => '',
                'placeholder' => 0,
                'prepend' => '',
                'append' => '',
                'min' => 0,
                'max' => '',
                'step' => '',
            ),
            array (
                'key' => 'field_58623f3c101bf',
                'label' => 'Intervalo de minutos',
                'name' => 'mgdbq2json_intervalo_minutos',
                'type' => 'number',
                'instructions' => '¿Qué margen de minutos se utiliza para controlar los intentos erróneos?',
                'required' => 1,
                'conditional_logic' => 0,
                'wrapper' => array (
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'user_roles' => array (
                    0 => 'all',
                ),
                'default_value' => '',
                'placeholder' => 0,
                'prepend' => '',
                'append' => '',
                'min' => 0,
                'max' => '',
                'step' => '',
            ),
        ),
        'location' => array (
            array (
                array (
                    'param' => 'options_page',
                    'operator' => '==',
                    'value' => 'mgdbq2json-config',
                ),
            ),
        ),
        'menu_order' => 0,
        'position' => 'normal',
        'style' => 'seamless',
        'label_placement' => 'top',
        'instruction_placement' => 'label',
        'hide_on_screen' => '',
        'active' => 1,
        'description' => '',
    ));

endif;