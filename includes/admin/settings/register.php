<?php
/**
 * Register settings
 *
 * @package     RoboBlog\Admin\Settings\Register
 * @since       1.0.0
 */


// Exit if accessed directly
if( ! defined( 'ABSPATH' ) ) exit;


/**
 * Retrieve the settings tabs
 *
 * @since       1.0.0
 * @return      array $tabs The registered settings tabs
 */
function roboblog_get_settings_tabs() {
    $settings = roboblog_get_registered_settings();

    $tabs               = array();
    $tabs['general']    = __( 'General', 'roboblog' );
    
    return apply_filters( 'roboblog_settings_tabs', $tabs );
}


/**
 * Retrieve the array of plugin settings
 *
 * @since       1.0.0
 * @return      array $roboblog_settings The registered settings
 */
function roboblog_get_registered_settings() {
    $roboblog_settings = array(
        // Settings
        'general' => apply_filters( 'roboblog_settings_general', array(
            array(
                'id'        => 'general_header',
                'name'      => __( 'General Settings', 'roboblog' ),
                'desc'      => '',
                'type'      => 'header'
            )
        ) ),
    );

    return apply_filters( 'roboblog_registered_settings', $roboblog_settings );
}


/**
 * Retrieve an option
 *
 * @since       1.0.0
 * @global      array $roboblog_options The RoboBlog options
 * @return      mixed
 */
function roboblog_get_option( $key = '', $default = false ) {
    global $roboblog_options;

    $value = ! empty( $roboblog_options[$key] ) ? $roboblog_options[$key] : $default;
    $value = apply_filters( 'roboblog_get_option', $value, $key, $default );

    return apply_filters( 'roboblog_get_option_' . $key, $value, $key, $default );
}


/**
 * Retrieve all options
 *
 * @since       1.0.0
 * @return      array $roboblog_options The RoboBlog options
 */
function roboblog_get_settings() {
    $roboblog_settings = get_option( 'roboblog_settings' );

    if( empty( $roboblog_settings ) ) {
        $roboblog_settings = array();

        update_option( 'roboblog_settings', $roboblog_settings );
    }

    return apply_filters( 'roboblog_get_settings', $roboblog_settings );
}


/**
 * Add settings sections and fields
 *
 * @since       1.0.0
 * @return      void
 */
function roboblog_register_settings() {
    if( get_option( 'roboblog_settings' ) == false ) {
        add_option( 'roboblog_settings' );
    }

    foreach( roboblog_get_registered_settings() as $tab => $settings ) {
        add_settings_section(
            'roboblog_settings_' . $tab,
            __return_null(),
            '__return_false',
            'roboblog_settings_' . $tab
        );

        foreach( $settings as $option ) {
            $name = isset( $option['name'] ) ? $option['name'] : '';

            add_settings_field(
                'roboblog_settings[' . $option['id'] . ']',
                $name,
                function_exists( 'roboblog_' . $option['type'] . '_callback' ) ? 'roboblog_' . $option['type'] . '_callback' : 'roboblog_missing_callback',
                'roboblog_settings_' . $tab,
                'roboblog_settings_' . $tab,
                array(
                    'section'       => $tab,
                    'id'            => isset( $option['id'] )           ? $option['id']             : null,
                    'desc'          => ! empty( $option['desc'] )       ? $option['desc']           : '',
                    'name'          => isset( $option['name'] )         ? $option['name']           : null,
                    'size'          => isset( $option['size'] )         ? $option['size']           : null,
                    'options'       => isset( $option['options'] )      ? $option['options']        : '',
                    'std'           => isset( $option['std'] )          ? $option['std']            : '',
                    'min'           => isset( $option['min'] )          ? $option['min']            : null,
                    'max'           => isset( $option['max'] )          ? $option['max']            : null,
                    'step'          => isset( $option['step'] )         ? $option['step']           : null,
                    'placeholder'   => isset( $option['placeholder'] )  ? $option['placeholder']    : null,
                    'rows'          => isset( $option['rows'] )         ? $option['rows']           : null,
                    'buttons'       => isset( $option['buttons'] )      ? $option['buttons']        : null,
                    'wpautop'       => isset( $option['wpautop'] )      ? $option['wpautop']        : null,
                    'teeny'         => isset( $option['teeny'] )        ? $option['teeny']          : null,
                    'notice'        => isset( $option['notice'] )       ? $option['notice']         : false,
                    'style'         => isset( $option['style'] )        ? $option['style']          : null,
                    'header'        => isset( $option['header'] )       ? $option['header']         : null,
                    'icon'          => isset( $option['icon'] )         ? $option['icon']           : null,
                    'class'         => isset( $option['class'] )        ? $option['class']          : null
                )
            );
        }
    }

    register_setting( 'roboblog_settings', 'roboblog_settings', 'roboblog_settings_sanitize' );
}
add_action( 'admin_init', 'roboblog_register_settings' );


/**
 * Settings sanitization
 *
 * @since       1.0.0
 * @param       array $input The value entered in the field
 * @global      array $roboblog_options The RoboBlog options
 * @return      string $input The sanitized value
 */
function roboblog_settings_sanitize( $input = array() ) {
    global $roboblog_options;

    if( empty( $_POST['_wp_http_referer'] ) ) {
        return $input;
    }
    
    parse_str( $_POST['_wp_http_referer'], $referrer );

    $settings   = roboblog_get_registered_settings();
    $tab        = isset( $referrer['tab'] ) ? $referrer['tab'] : 'general';

    $input = $input ? $input : array();
    $input = apply_filters( 'roboblog_settings_' . $tab . '_sanitize', $input );

    foreach( $input as $key => $value ) {
        $type = isset( $settings[$tab][$key]['type'] ) ? $settings[$tab][$key]['type'] : false;

        if( $type ) {
            // Field type specific filter
            $input[$key] = apply_filters( 'roboblog_settings_sanitize_' . $type, $value, $key );
        }

        // General filter
        $input[$key] = apply_filters( 'roboblog_settings_sanitize', $input[$key], $key );
    }

    if( ! empty( $settings[$tab] ) ) {
        foreach( $settings[$tab] as $key => $value ) {
            if( is_numeric( $key ) ) {
                $key = $value['id'];
            }

            if( empty( $input[$key] ) || ! isset( $input[$key] ) ) {
                unset( $roboblog_options[$key] );
            }
        }
    }

    // Merge our new settings with the existing
    $input = array_merge( $roboblog_options, $input );

    add_settings_error( 'roboblog-notices', '', __( 'Settings updated.', 'roboblog' ), 'updated' );

    return $input;
}


/**
 * Sanitize text fields
 *
 * @since       1.0.0
 * @param       array $input The value entered in the field
 * @return      string $input The sanitized value
 */
function roboblog_sanitize_text_field( $input ) {
    return trim( $input );
}
add_filter( 'roboblog_settings_sanitize_text', 'roboblog_sanitize_text_field' );


/**
 * Header callback
 *
 * @since       1.0.0
 * @param       array $args Arguments passed by the setting
 * @return      void
 */
function roboblog_header_callback( $args ) {
    echo '<hr />';
}


/**
 * Checkbox callback
 *
 * @since       1.0.0
 * @param       array $args Arguments passed by the setting
 * @global      array $roboblog_options The RoboBlog options
 * @return      void
 */
function roboblog_checkbox_callback( $args ) {
    global $roboblog_options;

    $checked = isset( $roboblog_options[$args['id']] ) ? checked( 1, $roboblog_options[$args['id']], false ) : '';

    $html  = '<input type="checkbox" id="roboblog_settings[' . $args['id'] . ']" name="roboblog_settings[' . $args['id'] . ']" value="1" ' . $checked . '/>&nbsp;';
    $html .= '<label for="roboblog_settings[' . $args['id'] . ']">' . $args['desc'] . '</label>';

    echo $html;
}


/**
 * Color callback
 *
 * @since       1.0.0
 * @param       array $args Arguments passed by the settings
 * @global      array $roboblog_options The RoboBlog options
 * @return      void
 */
function roboblog_color_callback( $args ) {
    global $roboblog_options;

    if( isset( $roboblog_options[$args['id']] ) ) {
        $value = $roboblog_options[$args['id']];
    } else {
        $value = isset( $args['std'] ) ? $args['std'] : '';
    }

    $default = isset( $args['std'] ) ? $args['std'] : '';
    $size    = ( isset( $args['size'] ) && ! is_null( $args['size'] ) ) ? $args['size'] : 'regular';

    $html  = '<input type="text" class="roboblog-color-picker" id="roboblog_settings[' . $args['id'] . ']" name="roboblog_settings[' . $args['id'] . ']" value="' . esc_attr( $value ) . '" data-default-color="' . esc_attr( $default ) . '" />&nbsp;';
    $html .= '<span class="roboblog-color-picker-label"><label for="roboblog_settings[' . $args['id'] . ']">' . $args['desc'] . '</label></span>';

    echo $html;
}


/**
 * Editor callback
 *
 * @since       1.0.0
 * @param       array $args Arguments passed by the setting
 * @global      array $roboblog_options The RoboBlog options
 * @return      void
 */
function roboblog_editor_callback( $args ) {
    global $roboblog_options;

    if( isset( $roboblog_options[$args['id']] ) ) {
        $value = $roboblog_options[$args['id']];
    } else {
        $value = isset( $args['std'] ) ? $args['std'] : '';
    }

    $rows       = ( isset( $args['rows'] ) && ! is_numeric( $args['rows'] ) ) ? $args['rows'] : '10';
    $wpautop    = isset( $args['wpautop'] ) ? $args['wpautop'] : true;
    $buttons    = isset( $args['buttons'] ) ? $args['buttons'] : true;
    $teeny      = isset( $args['teeny'] ) ? $args['teeny'] : false;

    wp_editor(
        $value,
        'roboblog_settings_' . $args['id'],
        array(
            'wpautop'       => $wpautop,
            'media_buttons' => $buttons,
            'textarea_name' => 'roboblog_settings[' . $args['id'] . ']',
            'textarea_rows' => $rows,
            'teeny'         => $teeny
        )
    );
    echo '<br /><label for="roboblog_settings[' . $args['id'] . ']">' . $args['desc'] . '</label>';
}


/**
 * Info callback
 *
 * @since       1.0.0
 * @param       array $args Arguments passed by the setting
 * @global      array $roboblog_options The RoboBlog options
 * @return      void
 */
function roboblog_info_callback( $args ) {
    global $roboblog_options;

    $notice = ( $args['notice'] == true ? '-notice' : '' );
    $class  = ( isset( $args['class'] ) ? $args['class'] : '' );
    $style  = ( isset( $args['style'] ) ? $args['style'] : 'normal' );
    $header = '';

    if( isset( $args['header'] ) ) {
        $header = '<b>' . $args['header'] . '</b><br />';
    }

    echo '<div id="roboblog_settings[' . $args['id'] . ']" name="roboblog_settings[' . $args['id'] . ']" class="roboblog-info' . $notice . ' roboblog-info-' . $style . '">';

    if( isset( $args['icon'] ) ) {
        echo '<p class="roboblog-info-icon">';
        echo '<i class="fa fa-' . $args['icon'] . ' ' . $class . '"></i>';
        echo '</p>';
    }

    echo '<p class="roboblog-info-desc">' . $header . $args['desc'] . '</p>';
    echo '</div>';
}


/**
 * Multicheck callback
 *
 * @since       1.0.0
 * @param       array $args Arguments passed by the setting
 * @global      array $roboblog_options The RoboBlog options
 * @return      void
 */
function roboblog_multicheck_callback( $args ) {
    global $roboblog_options;

    if( ! empty( $args['options'] ) ) {
        foreach( $args['options'] as $key => $option ) {
            $enabled = ( isset( $roboblog_options[$args['id']][$key] ) ? $option : NULL );

            echo '<input name="roboblog_settings[' . $args['id'] . '][' . $key . ']" id="roboblog_settings[' . $args['id'] . '][' . $key . ']" type="checkbox" value="' . $option . '" ' . checked( $option, $enabled, false ) . ' />&nbsp;';
            echo '<label for="roboblog_settings[' . $args['id'] . '][' . $key . ']">' . $option . '</label><br />';
        }
        echo '<p class="description">' . $args['desc'] . '</p>';
    }
}


/**
 * Number callback
 *
 * @since       1.0.0
 * @param       array $args Arguments passed by the setting
 * @global      array $roboblog_options The RoboBlog options
 * @return      void
 */
function roboblog_number_callback( $args ) {
    global $roboblog_options;

    if( isset( $roboblog_options[$args['id']] ) ) {
        $value = $roboblog_options[$args['id']];
    } else {
        $value = isset( $args['std'] ) ? $args['std'] : '';
    }

    $max    = isset( $args['max'] ) ? $args['max'] : 999999;
    $min    = isset( $args['min'] ) ? $args['min'] : 0;
    $step   = isset( $args['step'] ) ? $args['step'] : 1;
    $size   = ( isset( $args['size'] ) && ! is_null( $args['size'] ) ) ? $args['size'] : 'regular';

    $html  = '<input type="number" step="' . esc_attr( $step ) . '" max="' . esc_attr( $max ) . '" min="' . esc_attr( $min ) . '" class="' . $size . '-text" id="roboblog_settings[' . $args['id'] . ']" name="roboblog_settings[' . $args['id'] . ']" value="' . esc_attr( stripslashes( $value ) ) . '" />&nbsp;';
    $html .= '<label for="roboblog_settings[' . $args['id'] . ']">' . $args['desc'] . '</label>';

    echo $html;
}


/**
 * Password callback
 * 
 * @since       1.0.0
 * @param       array $args Arguments passed by the settings
 * @global      array $roboblog_options The RoboBlog options
 * @return      void
 */
function roboblog_password_callback( $args ) {
    global $roboblog_options;

    if( isset( $roboblog_options[$args['id']] ) ) {
        $value = $roboblog_options[$args['id']];
    } else {
        $value = isset( $args['std'] ) ? $args['std'] : '';
    }

    $size = ( isset( $args['size'] ) && ! is_null( $args['size'] ) ) ? $args['size'] : 'regular';

    $html  = '<input type="password" class="' . $size . '-text" id="roboblog_settings[' . $args['id'] . ']" name="roboblog_settings[' . $args['id'] . ']" value="' . esc_attr( $value )  . '" />&nbsp;';
    $html .= '<label for="roboblog_settings[' . $args['id'] . ']">' . $args['desc'] . '</label>';

    echo $html;
}


/**
 * Radio callback
 *
 * @since       1.0.0
 * @param       array $args Arguments passed by the setting
 * @global      array $roboblog_options The RoboBlog options
 * @return      void
 */
function roboblog_radio_callback( $args ) {
    global $roboblog_options;

    if( ! empty( $args['options'] ) ) {
        foreach( $args['options'] as $key => $option ) {
            $checked = false;

            if( isset( $roboblog_options[$args['id']] ) && $roboblog_options[$args['id']] == $key ) {
                $checked = true;
            } elseif( isset( $args['std'] ) && $args['std'] == $key && ! isset( $roboblog_options[$args['id']] ) ) {
                $checked = true;
            }

            echo '<input name="roboblog_settings[' . $args['id'] . ']" id="roboblog_settings[' . $args['id'] . '][' . $key . ']" type="radio" value="' . $key . '" ' . checked( true, $checked, false ) . '/>&nbsp;';
            echo '<label for="roboblog_settings[' . $args['id'] . '][' . $key . ']">' . $option . '</label><br />';
        }

        echo '<p class="description">' . $args['desc'] . '</p>';
    }
}


/**
 * Select callback
 * 
 * @since       1.0.0
 * @param       array $args Arguments passed by the setting
 * @global      array $roboblog_options The RoboBlog options
 * @return      void
 */
function roboblog_select_callback( $args ) {
    global $roboblog_options;

    if( isset( $roboblog_options[$args['id']] ) ) {
        $value = $roboblog_options[$args['id']];
    } else {
        $value = isset( $args['std'] ) ? $args['std'] : '';
    }

    $placeholder = isset( $args['placeholder'] ) ? $args['placeholder'] : '';

    $html = '<select id="roboblog_settings[' . $args['id'] . ']" name="roboblog_settings[' . $args['id'] . ']" placeholder="' . $placeholder . '" />';

    foreach( $args['options'] as $option => $name ) {
        $selected = selected( $option, $value, false );

        $html .= '<option value="' . $option . '" ' . $selected . '>' . $name . '</option>';
    }

    $html .= '</select>&nbsp;';
    $html .= '<label for="roboblog_settings[' . $args['id'] . ']">' . $args['desc'] . '</label>';

    echo $html;
}


/**
 * Text callback
 * 
 * @since       1.0.0
 * @param       array $args Arguments passed by the setting
 * @global      array $roboblog_options The RoboBlog options
 * @return      void
 */
function roboblog_text_callback( $args ) {
    global $roboblog_options;

    if( isset( $roboblog_options[$args['id']] ) ) {
        $value = $roboblog_options[$args['id']];
    } else {
        $value = isset( $args['std'] ) ? $args['std'] : '';
    }

    $size = ( isset( $args['size'] ) && ! is_null( $args['size'] ) ) ? $args['size'] : 'regular';

    $html  = '<input type="text" class="' . $size . '-text" id="roboblog_settings[' . $args['id'] . ']" name="roboblog_settings[' . $args['id'] . ']" value="' . esc_attr( stripslashes( $value ) )  . '" />&nbsp;';
    $html .= '<label for="roboblog_settings[' . $args['id'] . ']">' . $args['desc'] . '</label>';

    echo $html;
}


/**
 * Textarea callback
 * 
 * @since       1.0.0
 * @param       array $args Arguments passed by the setting
 * @global      array $roboblog_options The RoboBlog options
 * @return      void
 */
function roboblog_textarea_callback( $args ) {
    global $roboblog_options;

    if( isset( $roboblog_options[$args['id']] ) ) {
        $value = $roboblog_options[$args['id']];
    } else {
        $value = isset( $args['std'] ) ? $args['std'] : '';
    }

    $html  = '<textarea class="large-text" cols="50" rows="5" id="roboblog_settings[' . $args['id'] . ']" name="roboblog_settings[' . $args['id'] . ']">' . esc_textarea( stripslashes( $value ) ) . '</textarea>&nbsp;';
    $html .= '<label for="roboblog_settings[' . $args['id'] . ']">' . $args['desc'] . '</label>';

    echo $html;
}


/**
 * Upload callback
 * 
 * @since       1.0.0
 * @param       array $args Arguments passed by the setting
 * @global      array $roboblog_options The RoboBlog options
 * @return      void
 */
function roboblog_upload_callback( $args ) {
    global $roboblog_options;

    if( isset( $roboblog_options[$args['id']] ) ) {
        $value = $roboblog_options[$args['id']];
    } else {
        $value = isset( $args['std'] ) ? $args['std'] : '';
    }

    $size = ( isset( $args['size'] ) && ! is_null( $args['size'] ) ) ? $args['size'] : 'regular';

    $html  = '<input type="text" class="' . $size . '-text" id="roboblog_settings[' . $args['id'] . ']" name="roboblog_settings[' . $args['id'] . ']" value="' . esc_attr( stripslashes( $value ) ) . '" />&nbsp;';
    $html .= '<span><input type="button" class="roboblog_settings_upload_button button-secondary" value="' . __( 'Upload File', 'roboblog' ) . '" /></span>&nbsp;';
    $html .= '<label for="roboblog_settings[' . $args['id'] . ']">' . $args['desc'] . '</label>';

    echo $html;
}


/**
 * Hook callback
 *
 * @since       1.0.0
 * @param       array $args Arguments passed by the setting
 * @return      void
 */
function roboblog_hook_callback( $args ) {
    do_action( 'roboblog_' . $args['id'] );
}


/**
 * Missing callback
 *
 * @since       1.0.0
 * @param       array $args Arguments passed by the setting
 * @return      void
 */
function roboblog_missing_callback( $args ) {
    printf( __( 'The callback function used for the <strong>%s</strong> setting is missing.', 'roboblog' ), $args['id'] );
}
