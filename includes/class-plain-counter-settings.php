<?php

if ( ! defined( 'ABSPATH' ) ) exit;

class plain_counter_Settings {

	/**
	 * The single instance of plain-counter_Settings.
	 * @var 	object
	 * @access  private
	 * @since 	1.0.0
	 */
	private static $_instance = null;

	/**
	 * The main plugin object.
	 * @var 	object
	 * @access  public
	 * @since 	1.0.0
	 */
	public $parent = null;

	/**
	 * Prefix for plugin settings.
	 * @var     string
	 * @access  public
	 * @since   1.0.0
	 */
	public $base = '';

	/**
	 * Available settings for plugin.
	 * @var     array
	 * @access  public
	 * @since   1.0.0
	 */
	public $settings = array();

	public function __construct ( $parent ) {
		$this->parent = $parent;

		$this->base = 'grintender_pl_';

		// Initialise settings
		add_action( 'init', array( $this, 'init_settings' ), 11 );

		// Register plugin settings
		add_action( 'admin_init' , array( $this, 'register_settings' ) );

		// Add settings page to menu
		add_action( 'admin_menu' , array( $this, 'add_menu_item' ) );

		// Add settings link to plugins page
		add_filter( 'plugin_action_links_' . plugin_basename( $this->parent->file ) , array( $this, 'add_settings_link' ) );
	}

	/**
	 * Initialise settings
	 * @return void
	 */
	public function init_settings () {
		$this->settings = $this->settings_fields();
	}

	/**
	 * Add settings page to admin menu
	 * @return void
	 */
	public function add_menu_item () {
		$page = add_options_page( __( 'Plain Counter Settings', 'plain-counter' ) , __( 'Plain Counter Settings', 'plain-counter' ) , 'manage_options' , $this->parent->_token . '_settings' ,  array( $this, 'settings_page' ) );
		add_action( 'admin_print_styles-' . $page, array( $this, 'settings_assets' ) );
	}

	/**
	 * Load settings JS & CSS
	 * @return void
	 */
	public function settings_assets () {

		// We're including the farbtastic script & styles here because they're needed for the colour picker
		// If you're not including a colour picker field then you can leave these calls out as well as the farbtastic dependency for the wpt-admin-js script below
		wp_enqueue_style( 'farbtastic' );
    	wp_enqueue_script( 'farbtastic' );

    	// We're including the WP media scripts here because they're needed for the image upload field
    	// If you're not including an image upload then you can leave this function call out
    	wp_enqueue_media();

    	wp_register_script( $this->parent->_token . '-settings-js', $this->parent->assets_url . 'js/settings' . $this->parent->script_suffix . '.js', array( 'farbtastic', 'jquery' ), '1.0.0' );
    	wp_enqueue_script( $this->parent->_token . '-settings-js' );
	}

	/**
	 * Add settings link to plugin list table
	 * @param  array $links Existing links
	 * @return array 		Modified links
	 */
	public function add_settings_link ( $links ) {
		$settings_link = '<a href="options-general.php?page=' . $this->parent->_token . '_settings">' . __( 'Settings', 'plain-counter' ) . '</a>';
  		array_push( $links, $settings_link );
  		return $links;
	}

	/**
	 * Build settings fields
	 * @return array Fields to be displayed on settings page
	 */
	private function settings_fields () {

		$settings['layout'] = array(
			'title'					=> __( '1. Layout', 'plain-counter' ),
			'fields'				=> array(
         array(
					'id' 			=> 'cell_layout',
					'label'			=> __( 'Cells layout', 'plain-counter' ),
					'description'	=> __( 'Since its counter, one can not remove numbers, huh?', 'plain-counter' ),
					'type'			=> 'checkbox_multi',
					'options'		=> array( 'icon' => 'Font Awesome icon <br>', 'divider' => 'Divider <br>', 'text' => 'Caption' ),
					'default'		=> array()
				),
        array(
					'id' 			=> 'n_cells',
					'label'			=> __( 'How many cells aka counters do you want?' , 'plain-counter' ),
					'description'	=> __( 'Use even number between 2 and 6', 'plain-counter' ),
					'type'			=> 'number',
					'default'		=> '4',
                    'min'           => '2',
                    'max'           => '8',
					'placeholder'	=> __( '', 'plain-counter' )
				),
        array(
					'id' 			=> 'responsive_type',
					'label'			=> __( 'How much responsiveness do you need?', 'plain-counter' ),
					'description'	=> __( 'No time to explain, just fill in some stuff in Content tab and try different options yourself', 'plain-counter' ),
					'type'			=> 'radio',
					'options'		=> array('row' =>'Less','responsive' =>'More'),
                    'default'       => 'row',
            ),
      	array(
					'id' 			=> 'mobile-display',
					'label'			=> __( 'Hide on mobile?', 'plain-counter' ),
					'description'	=> __( 'Plain counter is responsive, but you are the boss. <br>If checked counter will be hidden on screens under 1000px', 'plain-counter' ),
					'type'			=> 'checkbox',
					'default'		=> ''
				),
	      array(
					'id' 			=> 'debug',
					'label'			=> __( 'Setup mode', 'plain-counter' ),
					'description'	=> __( 'If enabled numbers will be re-written with every page refresh. <br>Please, uncheck it when you are ready with set up', 'plain-counter' ),
					'type'			=> 'checkbox',
					'default'		=> 'true'
				)
			)
		);
                

		$settings['content'] = array(
			'title'					=> __( '2. Content', 'plain-counter' ),
			'description'		=> __( '', 'plain-counter' )
	  );

        $settings['style'] = array(
			'title'					=> __( '3.Style', 'plain-counter' ),
			'description'			=> __( '<div class="style-section"> We use color schemes from <a href="#">Google Material Design Palette</a> and recommend you to do the same.', 'plain-counter' ),
			'fields'				=> array(
				//bg
                get_option('grintender_pl_is_bg_solid', false) ?
                array( //SOLID
					'id' 			=> 'bg_solid',
					'label'			=> __( 'Enter any hex code (e.g. #eb5c69)', 'plain-counter' ),
					'description'	=> __( '<span class="section-name">background</span>', 'plain-counter' ),
					'type'			=> 'color',
					'default'		=> '#21759B'
				):
                array( //THEME
					'id' 			=> 'bg_theme',
					'label'			=> __( 'Pick a built-in theme', 'plain-counter' ),
					'description'	=> __( '<span class="section-name">background</span>', 'plain-counter' ),
					'type'			=> 'radio',
					'options'		=> array(
                        'red' =>
                        '<img class="theme-red">',

                        'pink' =>
                        '<img class="theme-pink">',

                        'purple' =>
                        '<img class="theme-purple">',

                        'deep-purple' =>
                        '<img class="theme-deep-purple">',

                        'indigo' =>
                        '<img class="theme-indigo">',

                        'blue' =>
                        '<img class="theme-blue">',

                        'light-blue' =>
                        '<img class="theme-light-blue">',

                        'cyan' =>
                        '<img class="theme-cyan">',

                        'teal' =>
                        '<img class="theme-teal">',

                        'green' =>
                        '<img class="theme-green">',

                        'light-green' =>
                        '<img class="theme-light-green">',

                        'lime' =>
                        '<img class="theme-lime">',

                        'yellow' =>
                        '<img class="theme-yellow">',

                        'amber' =>
                        '<img class="theme-amber">',

                        'orange' =>
                        '<img class="theme-orange">',

                        'deep-orange' =>
                        '<img class="theme-deep-orange">',

                        'brown' =>
                        '<img class="theme-brown">',

                        'grey' =>
                        '<img class="theme-grey">',

                        'blue-grey' =>
                        '<img class="theme-blue-grey">',
                    ),
					'default'		=> 'blue-grey'
				),
                array( //bg style toggle
					'id' 			=> 'is_bg_solid',
					'label'			=> __( 'Want to have same background for all cells isntead?', 'plain-counter' ),
					'description'	=> __( 'Yes, please', 'plain-counter' ),
					'type'			=> 'checkbox',
					'default'		=> ''
				),

                 array( //BORDERS outline
					'id' 			=> 'borders',
					'label'			=> __( 'Decide upon border style', 'plain-counter' ),
					'description'	=> __( '<span class="section-name">borders</span>', 'plain-counter' ),
					'type'			=> 'radio',
					'options'		=> array('none' => 'None',
                                             'outer' => 'Only outer',
                                             'each' => 'Each cell',
                                            ),
                    'default'		=> 'none'
				),
                array ( //BORDERS width
                     'id' 			=> 'border_width',
				     'label'			=> __( 'Border width' , 'plain-counter' ),
                     'description'	=> __( 'pixels', 'plain-counter' ),
                     'type'			=> 'number',
                      'min'           => '1',
                      'max'           => '10',
                      'default'		=> '1',
                      'placeholder'	=> __( '1', 'plain-counter' )
                      ),
                array( //BORDERS color
					'id' 			=> 'border_color',
					'label'			=> __( 'Pick a color, or enter your own hex code', 'plain-counter' ),
					'description'	=> __( 'Enter any hex code (e.g. #eb5c69)', 'plain-counter' ),
					'type'			=> 'color',
					'default'		=> '#eb5c69'
				),
                array( //BORDERS style
					'id' 			=> 'borders_style',
					'label'			=> __( 'Decide upon border style', 'plain-counter' ),
					'description'	=> __( '', 'plain-counter' ),
					'type'			=> 'radio',
					'options'		=> array('solid' => 'Solid', 'dashed' => 'Dashed'),
                    'default'		=> 'solid'
				),

                array ( //ELEMENTS size
                     'id' 			=> 'elements_size',
				     'label'			=> __( 'Elements size' , 'plain-counter' ),
                     'description'	=> __( 'pixels <span class="section-name">elements</span>', 'plain-counter' ),
                      'type'			=> 'number',
                      'min'           => '1',
                      'max'           => '50',
                      'default'		=> '16',
                      'placeholder'	=> __( '1', 'plain-counter' )
                    ),

        'icon_size' => ( get_option( 'grintender_pl_cell_layout', in_array('icon', true) )) ?

                array (//true if font awesome icons are used
                  'id' 			=> 'icon_size',
                  'label'			=> __( 'Want to have icons of different size?' , 'plain-counter' ),
                  'description'	=> __( 'enter value between 1 n 5', 'plain-counter' ),
                  'type'			=> 'number',
                  'min'           => '1',
                  'max'           => '5',
                  'default'		=> '2',
                  'placeholder'	=> __( '1', 'plain-counter' )

                ) : null,    //false

                array( //ELEMENTS color
					'id' 			=> 'elements_color',
					'label'			=> __( 'Elements color', 'plain-counter' ),
					'description'	=> __( 'Enter any hex code (e.g. #eb5c69)', 'plain-counter' ),
					'type'			=> 'color',
					'default'		=> '#eb5c69'
				),

				array(
					'id' 			=> 'custom_css',
					'label'			=> __( 'Custom CSS' , 'plain-counter' ),
					'description'	=> __( 'Are you CSS magician? <span class="section-name">custom</span>', 'plain-counter' ),
					'type'			=> 'textarea',
					'default'		=> '',
					'placeholder'	=> __( '', 'plain-counter' )
				)

		)
		);


		$settings['live'] = array(
			'title'					=> __( '4. Go live', 'plain-counter' ),
			'description'			=> __( '<span style="font-size:16px;">Congrats! Almost there <br><br>Once you are ready to go use <strong>[plain_counter]</strong> shortcode for posts and pages or <strong>echo do_shortcode("[plain_counter]")</strong> for template files. <br><br><i>(!)Dont forget to disable Set up mode in Layout tab.</i></span><div class="live-section">', 'plain-counter' )
		);

		$settings = apply_filters( $this->parent->_token . '_settings_fields', [
			'settings' => $settings,
			'current_tab' => $this->get_current_tab(),
			'current_section' => $this->get_current_section()
		]);

		return $settings;
	}

	/**
	 * Register plugin settings
	 * @return void
	 */
	public function register_settings () {
		if ( is_array( $this->settings ) ) {

			$current_tab = $this->get_current_tab();
			$current_section = $this->get_current_section();

			if ($current_section) {
				$sectionName = $current_section;
			} else {
				$sectionName = $current_tab;
			}

			$data = isset($this->settings[$sectionName]) ? $this->settings[$sectionName] : null;

			if (!$data) {
				return;
			}

			// Add section to page
			add_settings_section($sectionName, $data['title'], array( $this, 'settings_section' ), $this->parent->_token . '_settings' );

			if ( isset($data['fields']) ) {
				foreach ( $data['fields'] as $field ) {
					$validation = '';

					if ( isset( $field['callback'] ) ) {
						$validation = $field['callback'];
					}

					// Register field
					$option_name = $this->base . $field['id'];
					register_setting( $this->parent->_token . '_settings', $option_name, $validation );

					// Add field to page
					add_settings_field( $field['id'], $field['label'], array( $this->parent->admin, 'display_field' ), $this->parent->_token . '_settings', $sectionName, array( 'field' => $field, 'prefix' => $this->base ) );
				}
			}
		}
	}

	public function settings_section ( $section ) {
		$html = '<p> ' . $this->settings[ $section['id'] ]['description'] . '</p>' . "\n";
		echo $html;
	}

	/**
	 * Load settings page content
	 * @return void
	 */
	public function settings_page () {

		// Build page HTML
		$html = '<div class="wrap" id="' . $this->parent->_token . '_settings">' . "\n";
			$html .= '<h2>' . __( 'Plain Counter Settings' , 'plain-counter' ) . '</h2>' . "\n";

      /*
      **   branded header
      */


echo do_shortcode('<div class="preview-container">[plain_counter_branded]</div>');



      /*
      **   PREVIEW
      */
      echo '<script src="https://use.fontawesome.com/821cbf884f.js"></script>';
      echo do_shortcode('<div class="preview-container">[plain_counter]</div>');

			$tab = $this->get_current_tab();
			$current_section = $this->get_current_section();

			// Show page tabs
			if ( is_array( $this->settings ) && 1 < count( $this->settings ) ) {

				$html .= '<h2 class="nav-tab-wrapper">' . "\n";

				$c = 0;
				foreach ( $this->settings as $section => $data ) {
					if (isset($data['isSubsection']) && $data['isSubsection']) {
						continue;
					}

					// Set tab class
					$class = 'nav-tab';
					if ($section == $tab) {
						$class .= ' nav-tab-active';
					}

					// Set tab link
					$tab_link = add_query_arg( array( 'tab' => $section ) );
					if ( isset( $_GET['settings-updated'] ) ) {
						$tab_link = remove_query_arg( 'settings-updated', $tab_link );
					}

					$tab_link = remove_query_arg( 'section', $tab_link );

					// Output tab
					$html .= '<a href="' . $tab_link . '" class="' . esc_attr( $class ) . '">' . esc_html( $data['title'] ) . '</a>' . "\n";

					++$c;
				}

				$html .= '</h2>' . "\n";
			}

			// show tab sections
			$html .= $this->get_current_tab_sections_html();

			$html .= '<form method="post" action="options.php" enctype="multipart/form-data">' . "\n";

				// Get settings fields
				ob_start();
				settings_fields( $this->parent->_token . '_settings' );
				do_settings_sections( $this->parent->_token . '_settings' );
				$html .= ob_get_clean();

				$tabName = $current_section ? $current_section : $tab;

				$html .= '<p class="submit">' . "\n";
					$html .= '<input type="hidden" name="tab" value="' . esc_attr( $tabName ) . '" />' . "\n";
					$html .= '<input name="Submit" type="submit" class="button-primary" value="' . esc_attr( __( 'Save Settings' , 'plain-counter' ) ) . '" />' . "\n";
				$html .= '</p>' . "\n";
			$html .= '</form>' . "\n";
		$html .= '</div>' . "\n";

		echo $html;
	}

	public function get_current_tab() {
		$tab = '';
		if ( isset( $_REQUEST['tab'] ) && $_REQUEST['tab'] ) {
			$tab .= $_REQUEST['tab'];
		} else {
			reset($this->settings);
			$tab = key($this->settings);
		}

		return $tab;
	}

	public function get_current_section() {
		$sections = $this->get_current_tab_sections();

		if ( empty( $sections ) || 0 === sizeof( $sections ) ) {
			return '';
		}

		$current_section = '';

		if ( isset( $_GET['section'] ) && $_GET['section'] && isset($sections[$_GET['section']])) {
			$current_section .= $_GET['section'];
		} else {
			reset($sections);
			$current_section = key($sections);
		}

		return $current_section;
	}

	/**
	 * Get sections.
	 *
	 * @return array
	 */
	public function get_current_tab_sections() {
		return apply_filters( $this->parent->_token . '_get_tab_sections', $this->get_current_tab());
	}

	/**
	 * Returns sections html.
	 */
	public function get_current_tab_sections_html() {
		$sections = $this->get_current_tab_sections();

		if ( empty( $sections ) || 0 === sizeof( $sections ) ) {
			return '';
		}

		$current_tab = $this->get_current_tab();
		$current_section = $this->get_current_section();

		$html = '<ul class="subsubsub">';

		$array_keys = array_keys( $sections );

		foreach ( $sections as $id => $title ) {
			$link = add_query_arg( array( 'tab' => $current_tab, 'section' => $id ) );

			if ( isset( $_GET['settings-updated'] ) ) {
				$link = remove_query_arg( 'settings-updated', $link );
			}

			$html .=  '<li><a href="' . $link . '" class="' . ( $current_section == $id ? 'current' : '' ) . '">' . esc_html( $title ) . '</a> ' . ( end( $array_keys ) == $id ? '' : '|' ) . ' </li>';
		}

		$html .= '</ul><br class="clear" />';

		return $html;
	}

	/**
	 * Main plain-counter_Settings Instance
	 *
	 * Ensures only one instance of plain-counter_Settings is loaded or can be loaded.
	 *
	 * @since 1.0.0
	 * @static
	 * @see plain-counter()
	 * @return Main plain-counter_Settings instance
	 */
	public static function instance ( $parent ) {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self( $parent );
		}
		return self::$_instance;
	} // End instance()

	/**
	 * Cloning is forbidden.
	 *
	 * @since 1.0.0
	 */
	public function __clone () {
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?' ), $this->parent->_version );
	} // End __clone()

	/**
	 * Unserializing instances of this class is forbidden.
	 *
	 * @since 1.0.0
	 */
	public function __wakeup () {
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?' ), $this->parent->_version );
	} // End __wakeup()

}
