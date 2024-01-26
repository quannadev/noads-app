<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://dl.quanna.dev
 * @since      1.0.0
 *
 * @package    Get_Link
 * @subpackage Get_Link/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Get_Link
 * @subpackage Get_Link/includes
 * @author     Quanna <me@quanna.dev>
 */
class Get_Link {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Get_Link_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'GET_LINK_VERSION' ) ) {
			$this->version = GET_LINK_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'get-link';

        add_shortcode('get_link_form', array($this, 'get_link_form'));

        // Handle AJAX requests
        add_action('wp_ajax_handle_link_request', array($this, 'handle_link_request'));
        add_action('wp_ajax_nopriv_handle_link_request', array($this, 'handle_link_request'));

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();
	}


	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Get_Link_Loader. Orchestrates the hooks of the plugin.
	 * - Get_Link_i18n. Defines internationalization functionality.
	 * - Get_Link_Admin. Defines all hooks for the admin area.
	 * - Get_Link_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-get-link-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-get-link-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-get-link-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-get-link-public.php';

		$this->loader = new Get_Link_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Get_Link_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Get_Link_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Get_Link_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Get_Link_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Get_Link_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

	 /**
     * Get the form for the plugin
     *
     * @since 1.0.0
     * @return string
     */
    public function get_link_form() {
        ob_start();
        ?>
        <form id="get-link-form" action="#" method="post">
            <label for="link_url">Enter your link URL:</label>
            <input type="url" name="link_url" id="link_url" placeholder="https://example.com" required>
            <input type="submit" value="Get Link">
            <div id="link-result"></div>
            <?php wp_nonce_field('get_link_nonce', 'get_link_nonce'); ?>
        </form>

        <script>
            // JavaScript code for handling AJAX form submission
            jQuery(document).ready(function($) {
                $('#get-link-form').submit(function() {
                    var linkUrl = $('#link_url').val();

                    $.ajax({
                        type: 'post',
                        url: get_link_ajax_object.ajax_url,
                        data: {
                            action: 'handle_link_request',
                            link_url: linkUrl,
                            nonce: $('#get_link_nonce').val()
                        },
                        success: function(response) {
                            $('#link-result').html(response);
                        }
                    });

                    return false; // Prevent the form from submitting in the traditional way
                });
            });
        </script>
        <?php

        return ob_get_clean();
    }

    // AJAX callback function
    public function handle_link_request() {
        check_ajax_referer('get_link_nonce', 'nonce');

        $link_url = isset($_POST['link_url']) ? esc_url($_POST['link_url']) : '';

        // Process the link URL as needed
        // For demonstration purposes, simply echo the link URL

        echo 'Your link URL is: ' . $link_url;

        wp_die(); // This is required to terminate immediately and return a proper response
    }
}
