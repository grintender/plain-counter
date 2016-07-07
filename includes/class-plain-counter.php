<?php

if ( ! defined( 'ABSPATH' ) ) exit;

class Plain_Counter {

	/**
	 * The single instance of Plain_Counter.
	 * @var 	object
	 * @access  private
	 * @since 	1.0.0
	 */
	private static $_instance = null;

	/**
	 * Settings class object
	 * @var     object
	 * @access  public
	 * @since   1.0.0
	 */
	public $settings = null;

	/**
	 * The version number.
	 * @var     string
	 * @access  public
	 * @since   1.0.0
	 */
	public $_version;

	/**
	 * The token.
	 * @var     string
	 * @access  public
	 * @since   1.0.0
	 */
	public $_token;

	/**
	 * The main plugin file.
	 * @var     string
	 * @access  public
	 * @since   1.0.0
	 */
	public $file;

	/**
	 * The main plugin directory.
	 * @var     string
	 * @access  public
	 * @since   1.0.0
	 */
	public $dir;

	/**
	 * The plugin assets directory.
	 * @var     string
	 * @access  public
	 * @since   1.0.0
	 */
	public $assets_dir;

	/**
	 * The plugin assets URL.
	 * @var     string
	 * @access  public
	 * @since   1.0.0
	 */
	public $assets_url;

	/**
	 * Suffix for Javascripts.
	 * @var     string
	 * @access  public
	 * @since   1.0.0
	 */
	public $script_suffix;

	/**
	 * Constructor function.
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */
	public function __construct ( $file = '', $version = '1.0.0' ) {
		$this->_version = $version;
		$this->_token = 'plain_counter';

		// Load plugin environment variables
		$this->file = $file;
		$this->dir = dirname( $this->file );
		$this->assets_dir = trailingslashit( $this->dir ) . 'assets';
		$this->assets_url = esc_url( trailingslashit( plugins_url( '/assets/', $this->file ) ) );

		/*$this->script_suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';*/

		register_activation_hook( $this->file, array( $this, 'install' ) );

		// Load frontend JS & CSS
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ), 10 );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ), 10 );

		// Load admin JS & CSS
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ), 10, 1 );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_styles' ), 10, 1 );

		// Load API for generic admin functions
		if ( is_admin() ) {
			$this->admin = new Plain_Counter_Admin_API();
		}

		// Handle localisation
		$this->load_plugin_textdomain();
		add_action( 'init', array( $this, 'load_localisation' ), 0 );
	} // End __construct ()

	/**
	 * Wrapper function to register a new post type
	 * @param  string $post_type   Post type name
	 * @param  string $plural      Post type item plural name
	 * @param  string $single      Post type item single name
	 * @param  string $description Description of post type
	 * @return object              Post type class object
	 */
	public function register_post_type ( $post_type = '', $plural = '', $single = '', $description = '', $options = array() ) {

		if ( ! $post_type || ! $plural || ! $single ) return;

		$post_type = new Plain_Counter_Post_Type( $post_type, $plural, $single, $description, $options );

		return $post_type;
	}

	/**
	 * Wrapper function to register a new taxonomy
	 * @param  string $taxonomy   Taxonomy name
	 * @param  string $plural     Taxonomy single name
	 * @param  string $single     Taxonomy plural name
	 * @param  array  $post_types Post types to which this taxonomy applies
	 * @return object             Taxonomy class object
	 */
	public function register_taxonomy ( $taxonomy = '', $plural = '', $single = '', $post_types = array(), $taxonomy_args = array() ) {

		if ( ! $taxonomy || ! $plural || ! $single ) return;

		$taxonomy = new Plain_Counter_Taxonomy( $taxonomy, $plural, $single, $post_types, $taxonomy_args );

		return $taxonomy;
	}

	/**
	 * Load frontend CSS.
	 * @access  public
	 * @since   1.0.0
	 * @return void
	 */
	public function enqueue_styles () {
		wp_register_style( $this->_token . '-frontend', esc_url( $this->assets_url ) . 'css/frontend.css', array(), $this->_version );
		wp_enqueue_style( $this->_token . '-frontend' );
	} // End enqueue_styles ()


    /**
	 * Load frontend Javascript.
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */
	public function enqueue_scripts () {
		wp_register_script( $this->_token . '-frontend', esc_url( $this->assets_url ) . 'js/frontend' . $this->script_suffix . '.js', array( 'jquery' ), $this->_version );
		wp_enqueue_script( $this->_token . '-frontend' );

    $saved_settings = array(
      'num_nature' => get_option('grintender_pl_static_dynamic2'), // static or dynamic value
      'static' => get_option('grintender_pl_static_number_field'), // if static return this value
      //if dynamic here are params:
      'reset' => get_option( 'grintender_pl_reset_logic' ),   // 1, 30, 365 or "never" values
      'increase_step' => get_option( 'grintender_pl_increase_step' ), //1, 30, 365 values

      'dynamic_min1' => get_option( 'grintender_pl_dynamic_min_1'), //args for defining increase interval
      'dynamic_max1' => get_option( 'grintender_pl_dynamic_max_1'),
      'start_date'   => get_option( 'grintender_pl_start_date1'),

      'debug' => get_option( 'grintender_pl_debug', false ),
      'number' => get_option('grintender_pl_n_cells'),
    );

    $number = get_option('grintender_pl_n_cells');
    $php_settings = [];

    for ($i = 1; $i <= $number; $i++) {
        $php_settings['num_nature_' . $i] = get_option('grintender_pl_static_dynamic_' . $i);
        $php_settings['static_' . $i] = get_option('grintender_pl_static_number_' . $i);

        $php_settings['reset_' . $i] = get_option( 'grintender_pl_reset_logic_' . $i);
        $php_settings['increase_step_' . $i] = get_option( 'grintender_pl_increase_step_' . $i);

        $php_settings['dynamic_min_' . $i] = get_option( 'grintender_pl_dynamic_min_' . $i);
        $php_settings['dynamic_max_' . $i] = get_option( 'grintender_pl_dynamic_max_' . $i);

        $php_settings['start_date_' . $i] = get_option( 'grintender_pl_start_date_' . $i);
        $php_settings['dynamic_max_' . $i] = get_option( 'grintender_pl_dynamic_max_' . $i);
        $php_settings['dynamic_min_' . $i] = get_option( 'grintender_pl_dynamic_min_' . $i);

        $php_settings['num_nature_' . $i] = get_option('grintender_pl_static_dynamic_' . $i); // static or dynamic value
        $php_settings['static_' . $i] = get_option('grintender_pl_static_number_' . $i); // if static return this value

        // if dynamic here are params:
        $php_settings['reset_' . $i] = get_option( 'grintender_pl_reset_logic_' . $i);   // 1, 30, 365 or "never" values
        $php_settings['increase_step_' . $i] = get_option( 'grintender_pl_increase_step_' . $i ); //1, 30, 365 values

        $php_settings['dynamic_min_' . $i] = get_option( 'grintender_pl_dynamic_min_' . $i); //args for defining increase interval
        $php_settings['dynamic_max_' . $i] = get_option( 'grintender_pl_dynamic_max_' . $i);
        $php_settings['start_date_' . $i] = get_option( 'grintender_pl_start_date_' . $i);
        $php_settings['debug'] = get_option( 'grintender_pl_debug', false );
        $php_settings['number'] = get_option('grintender_pl_n_cells');
			}

		  wp_localize_script($this->_token . '-frontend', 'php_vars', $php_settings);

			return $php_settings;
	} // End enqueue_scripts ()

	/**
	 * Load admin CSS.
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */
	public function admin_enqueue_styles ( $hook = '' ) {
		wp_register_style( $this->_token . '-admin', esc_url( $this->assets_url ) . 'css/admin.css', array(), $this->_version );
		wp_enqueue_style( $this->_token . '-admin' );
	} // End admin_enqueue_styles ()

	/**
	 * Load admin Javascript.
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */
/*	public function admin_enqueue_scripts ( $hook = '' ) {
		wp_register_script( $this->_token . '-admin', esc_url( $this->assets_url ) . 'js/admin' . $this->script_suffix . '.js', array( 'jquery' ), $this->_version );
		wp_enqueue_script( $this->_token . '-admin' );


	} */ // End admin_enqueue_scripts ()

    public function admin_enqueue_scripts () {

        $this->enqueue_scripts();

	}

	/**
	 * Load plugin localisation
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */
	public function load_localisation () {
		load_plugin_textdomain( 'plain-counter', false, dirname( plugin_basename( $this->file ) ) . '/lang/' );
	} // End load_localisation ()

	/**
	 * Load plugin textdomain
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */
	public function load_plugin_textdomain () {
	    $domain = 'plain-counter';

	    $locale = apply_filters( 'plugin_locale', get_locale(), $domain );

	    load_textdomain( $domain, WP_LANG_DIR . '/' . $domain . '/' . $domain . '-' . $locale . '.mo' );
	    load_plugin_textdomain( $domain, false, dirname( plugin_basename( $this->file ) ) . '/lang/' );
	} // End load_plugin_textdomain ()

	/**
	 * Main Plain_Counter Instance
	 *
	 * Ensures only one instance of Plain_Counter is loaded or can be loaded.
	 *
	 * @since 1.0.0
	 * @static
	 * @see Plain_Counter()
	 * @return Main Plain_Counter instance
	 */
	public static function instance ( $file = '', $version = '1.0.0' ) {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self( $file, $version );
		}
		return self::$_instance;
	} // End instance ()

	/**
	 * Cloning is forbidden.
	 *
	 * @since 1.0.0
	 */
	public function __clone () {
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?' ), $this->_version );
	} // End __clone ()

	/**
	 * Unserializing instances of this class is forbidden.
	 *
	 * @since 1.0.0
	 */
	public function __wakeup () {
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?' ), $this->_version );
	} // End __wakeup ()

	/**
	 * Installation. Runs on activation.
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */
	public function install () {
		$this->_log_version_number();
	} // End install ()

	/**
	 * Log the plugin version number.
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */
	private function _log_version_number () {
		update_option( $this->_token . '_version', $this->_version );
	} // End _log_version_number ()

}
