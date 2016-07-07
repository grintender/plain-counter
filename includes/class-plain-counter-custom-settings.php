<?php

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Create the section beneath the tab
 **/
add_filter( 'plain_counter_settings_fields', 'plain_counter_register_additional_settings' );
function plain_counter_register_additional_settings($params) {
	$settings = $params['settings'];
	$currentTab = $params['current_tab'];
	$currentSection = $params['current_section'];

  $number = get_option('grintender_pl_n_cells');

  for ($i = 1; $i <= $number; $i++) {

$settings['tab' . $i] = array(
		'isSubsection'  => true,
		'title'					=> __( '', 'plain-counter' ),
		'description'		    => __( 'Save settings every time you done filling things for a cell', 'plain-counter' ),
		'fields'				=> array(
			            array (
                                    'id' 			=> 'icon_' . $i,
				                    'label'			=> __( 'Icon' , 'plain-counter' ),
                                    'description'	=> __( 'Fill in fontawesome icon name. <br><a href="http://fontawesome.io/icons/" target="_blank"> Check this page</a> for available icons and their names. ', 'plain-counter' ),
                                    'type'			=> 'text',
                                    'default'		=> 'envira',
                                    'placeholder'	=> __( 'e.g. facebook', 'plain-counter' )

                        ),
                        array (
                                    'id' 			=> 'caption_' . $i,
				                    'label'			=> __( 'Text' , 'plain-counter' ),
                                    'description'	=> __( 'This is a standard text field.', 'plain-counter' ),
                                    'type'			=> 'text',
                                    'default'		=> 'cups of green tea consumed today',
                                    'placeholder'	=> __( 'cups of coffee consumed', 'plain-counter' )

                        ),


                        array( // setting the number type here

                                   'id' 			=> 'static_dynamic_' . $i,
					               'label'			=> __( 'Static counter value or dynamic? ', 'plain-counter' ),
					               'description'	=> __( 'Dynamic values will be automatically updated by means of our super math function and you ultimate will.<br> Hit "save settings" to refresh page and see extra fields for dynamic value', 'plain-counter' ),
					               'type'			=> 'radio',
					               'options'		=> array( false => 'Static <br>', true => 'Dynamic'),
					               'default'		=> 'false'
				                        ),

                        'number' 				=> ( get_option( 'grintender_pl_static_dynamic_' . $i, false )) ?

                        array ( //true = dynamic
                                    'id' 			=> 'dynamic_min_' . $i,
					                'label'			=> __( 'Increase interval minimum value' , 'plain-counter' ),
					                'description'	=> __( 'For example, if you drink between 3 and 7 cups of green tea every day <br> this value would be 3', 'plain-counter' ),
					                'type'			=> 'number',
				                    'default'		=> '',
					                'placeholder'	=> __( '42', 'plain-counter' )

                        )  :

                        array ( //false = static
                                    'id' 			=> 'static_number_' . $i,
					                'label'			=> __( 'Static value' , 'plain-counter' ),
					                'description'	=> __( 'Rock solid, never changing number', 'plain-counter' ),
					                'type'			=> 'number',
				                    'default'		=> '5',
					                'placeholder'	=> __( '', 'plain-counter' )

                        ),

                        'number_max' 		=> ( get_option( 'grintender_pl_static_dynamic_' . $i, false )) ?
                        array ( //true = dynamic
                                    'id' 			=> 'dynamic_max_' . $i,
					                'label'			=> __( 'Increase interval maximum value' , 'plain-counter' ),
					                'description'	=> __( 'and this one 7 then', 'plain-counter' ),
					                'type'			=> 'number',
				                    'default'		=> '',
					                'placeholder'	=> __( '42', 'plain-counter' )

                        ) : null,    //false = static

                        'number_increase_logic' 	=> ( get_option( 'grintender_pl_static_dynamic_' . $i, false )) ?

                        array ( //true = dynamic
                                    'id' 			=> 'increase_step_' . $i,
					                'label'			=> __( 'Increase value once a', 'plain-counter' ),
					                'description'	=> __( '', 'plain-counter' ),
					                'type'			=> 'radio',
					                'options'		=> array( '1' => 'Day', '30' => 'Month', '365' => 'Year'),
					                'default'		=> ''

                        ) : null,    //false = static

                         'number_reset_logic' 		=> ( get_option( 'grintender_pl_static_dynamic_' . $i, false )) ?

                        array ( //true = dynamic
                                    'id' 			=> 'reset_logic_' . $i,
					                'label'			=> __( 'How often should we reset counter?', 'plain-counter' ),
					                'description'	=> __( 'If you choose "never" hit Save and enter start date', 'plain-counter' ),
					                'type'			=> 'radio',
					                'options'		=> array( '1' => 'Daily', '30' => 'Monthly', '365' => 'Yearly', 'never' => 'Never' ),
					                'default'		=> 'daily'

                        ) : null,    //false = static

                    'js_fallback_value' 		=> ( get_option( 'grintender_pl_static_dynamic_' . $i, false )) ?
                        array (
                                    'id' 			=> 'js_fallback_value_' . $i,
					                'label'			=> __( 'JS fallback value' , 'plain-counter' ),
					                'description'	=> __( 'In 2nd decade of XXI century some people still use Internet Explorer, <br>and few people (less than 1%) have JavaScript disabled. In a name of compassion enter some value for them', 'plain-counter' ),
					                'type'			=> 'number',
				                    'default'		=> '',
					                'placeholder'	=> __( '43', 'plain-counter' )

                        ) : null,

                 'reset_logic_start_date' => ( get_option( 'grintender_pl_reset_logic_' . $i)
                                                       == "never" && get_option( 'grintender_pl_static_dynamic_' . $i))?
                        array( //if never is the case ask for a date
					'id' 			=> 'start_date_' . $i,
					'label'			=> __( 'Start date' , 'plain-counter' ),
					'description'	=> __( 'This is a standard text field.', 'plain-counter' ),
					'type'			=> 'text',
					'default'		=> '',
					'placeholder'	=> __( 'Placeholder text', 'plain-counter' )
				) : null

                    )
	);


        }

	return $settings;
}

add_filter( 'plain_counter_get_tab_sections', 'plain_counter_register_tab_sections' );
function plain_counter_register_tab_sections( $currentTab ) {
	$sections = [];

    if ($currentTab === 'content') {

        $number = get_option('grintender_pl_n_cells');

        for ($i = 1; $i <= $number; $i++) {
            $sections['tab' .$i] = __( 'Cell ' .$i, 'plain-counter' );
	}

    }

	return $sections;

}
