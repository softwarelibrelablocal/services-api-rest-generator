<?php

if( function_exists('acf_add_local_field_group') ):

    acf_add_local_field_group(array (
        'key' => 'group_582839a2bebd2',
        'title' => 'Conectores',
        'fields' => array (
            array (
                'key' => 'field_582839c7ff693',
                'label' => 'Descripción',
                'name' => 'mgdbq2josn_descripcion',
                'type' => 'textarea',
                'instructions' => 'Indique alguna descripción para identificar mejor la conexión a la base de datos.',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array (
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'default_value' => '',
                'placeholder' => '',
                'maxlength' => '',
                'rows' => 4,
                'new_lines' => 'br',
            ),
            array (
                'key' => 'field_58283a30ff695',
                'label' => 'Motor de base de datos',
                'name' => 'mgdbq2josn_dbengine',
                'type' => 'select',
                'instructions' => 'Indique el motor de la base de datos',
                'required' => 1,
                'conditional_logic' => 0,
                'wrapper' => array (
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'choices' => array (
                    'oracle' => 'Oracle',
                    'mssql' => 'Microsoft SQL Server',
                    'mysql' => 'MySQL',
                ),
                'default_value' => array (
                ),
                'allow_null' => 0,
                'multiple' => 0,
                'ui' => 0,
                'ajax' => 0,
                'return_format' => 'array',
                'placeholder' => '',
            ),
            array (
                'key' => 'field_582839fdff694',
                'label' => 'Cadena de conexión',
                'name' => 'mgdbq2josn_connstring',
                'type' => 'text',
                'instructions' => 'Indique la cadena de conexión.',
                'required' => 1,
                'conditional_logic' => array (
                    array (
                        array (
                            'field' => 'field_58283a30ff695',
                            'operator' => '==',
                            'value' => 'oracle',
                        ),
                    ),
                ),
                'wrapper' => array (
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'default_value' => '',
                'placeholder' => '',
                'prepend' => '',
                'append' => '',
                'maxlength' => '',
            ),
            array (
                'key' => 'field_583d3ec576680',
                'label' => 'Servidor',
                'name' => 'mgdbq2josn_servername',
                'type' => 'text',
                'instructions' => 'Indique el nombre/IP del servidor.',
                'required' => 1,
                'conditional_logic' => array (
                    array (
                        array (
                            'field' => 'field_58283a30ff695',
                            'operator' => '==',
                            'value' => 'mysql',
                        ),
                    ),
                    array (
                        array (
                            'field' => 'field_58283a30ff695',
                            'operator' => '==',
                            'value' => 'mssql',
                        ),
                    ),
                ),
                'wrapper' => array (
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'default_value' => '',
                'placeholder' => '',
                'prepend' => '',
                'append' => '',
                'maxlength' => '',
            ),
            array (
                'key' => 'field_583d4166ca441',
                'label' => 'Nombre de la base de datos',
                'name' => 'mgdbq2josn_database_name',
                'type' => 'text',
                'instructions' => '',
                'required' => 1,
                'conditional_logic' => array (
                    array (
                        array (
                            'field' => 'field_58283a30ff695',
                            'operator' => '==',
                            'value' => 'mysql',
                        ),
                    ),
                    array (
                        array (
                            'field' => 'field_58283a30ff695',
                            'operator' => '==',
                            'value' => 'mssql',
                        ),
                    ),
                ),
                'wrapper' => array (
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'default_value' => '',
                'placeholder' => '',
                'prepend' => '',
                'append' => '',
                'maxlength' => '',
            ),
            array (
                'key' => 'field_5836d90170d5a',
                'label' => 'Nombre de usuario',
                'name' => 'mgdbq2josn_username',
                'type' => 'text',
                'instructions' => '',
                'required' => 1,
                'conditional_logic' => array (
                    array (
                        array (
                            'field' => 'field_58283a30ff695',
                            'operator' => '==',
                            'value' => 'oracle',
                        ),
                    ),
                    array (
                        array (
                            'field' => 'field_58283a30ff695',
                            'operator' => '==',
                            'value' => 'mysql',
                        ),
                    ),
                    array (
                        array (
                            'field' => 'field_58283a30ff695',
                            'operator' => '==',
                            'value' => 'mssql',
                        ),
                    ),
                ),
                'wrapper' => array (
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'default_value' => '',
                'placeholder' => '',
                'prepend' => '',
                'append' => '',
                'maxlength' => '',
            ),
            array (
                'key' => 'field_5836d91d70d5b',
                'label' => 'Contraseña',
                'name' => 'mgdbq2josn_password',
                'type' => 'text',
                'instructions' => '',
                'required' => 1,
                'conditional_logic' => array (
                    array (
                        array (
                            'field' => 'field_58283a30ff695',
                            'operator' => '==',
                            'value' => 'oracle',
                        ),
                    ),
                    array (
                        array (
                            'field' => 'field_58283a30ff695',
                            'operator' => '==',
                            'value' => 'mysql',
                        ),
                    ),
                    array (
                        array (
                            'field' => 'field_58283a30ff695',
                            'operator' => '==',
                            'value' => 'mssql',
                        ),
                    ),
                ),
                'wrapper' => array (
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'default_value' => '',
                'placeholder' => '',
                'prepend' => '',
                'append' => '',
                'maxlength' => '',
            ),
        ),
        'location' => array (
            array (
                array (
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'mg_connector',
                ),
            ),
        ),
        'menu_order' => 0,
        'position' => 'normal',
        'style' => 'seamless',
        'label_placement' => 'top',
        'instruction_placement' => 'label',
        'hide_on_screen' => array (
            0 => 'permalink',
            1 => 'the_content',
            2 => 'excerpt',
            3 => 'custom_fields',
            4 => 'discussion',
            5 => 'comments',
            6 => 'revisions',
            7 => 'slug',
            8 => 'author',
            9 => 'format',
            10 => 'page_attributes',
            11 => 'featured_image',
            12 => 'categories',
            13 => 'tags',
            14 => 'send-trackbacks',
        ),
        'active' => 1,
        'description' => '',
    ));

endif;

function action_function_name( $field ) {

    if($field['key'] == "field_5836d91d70d5b") {
        ?>
        <div id="connector-checker">
            <?php wp_nonce_field("check_connector", "check_connector_nonce"); ?>
            <a id="btn-check-connector" class="button button-primary button-large button-disabled"><?php esc_html_e("Probar conexión","mgdbq2json"); ?></a>
            <span class="spinner is-active"></span>
            <span class="message success"><?php _e("Conexión exitosa", "mgdbq2json"); ?></span>
            <span class="message failure"><?php _e("Conexión fallida", "mgdbq2json"); ?></span>
        </div>
        <?php
    }

}
add_action( 'acf/render_field/type=text', 'action_function_name', 20, 1 );