<?php

class Logy {

    protected $plugin_slug;

    protected $version;

    public function __construct() {

        // Init Data.
        $this->version = '1.0.0';
        $this->plugin_slug = 'logy-slug';

        // Load Functions.
        $this->init_logy();

        // Load Global Variables.
        $this->logy_globals();

        // Load Text Domain
        add_action( 'init', array( &$this, 'load_textdomain' ) );

    }

    /**
     * # Init Logy Files
     */
    private function init_logy() {

        // General Functions
        require_once LOGY_CORE . 'functions/logy-admin-functions.php';
        require_once LOGY_CORE . 'functions/logy-scripts-functions.php';
        require_once LOGY_CORE . 'functions/logy-general-functions.php';
        require_once LOGY_CORE . 'functions/logy-social-functions.php';

        // Classes
        require_once LOGY_CORE . 'class-logy-widgets.php';
        require_once LOGY_CORE . 'class-logy-fields.php';
        require_once LOGY_CORE . 'class-logy-mail.php';
        require_once LOGY_CORE . 'class-logy-form.php';
        require_once LOGY_CORE . 'class-logy-setup.php';
        require_once LOGY_CORE . 'class-logy-query.php';
        require_once LOGY_CORE . 'class-logy-social.php';
        require_once LOGY_CORE . 'class-logy-rewrite.php';
        require_once LOGY_CORE . 'class-logy-styling.php';
        require_once LOGY_CORE . 'class-logy-widgets.php';
        require_once LOGY_CORE . 'class-logy-limit.php';

        // Include Main Pages
        require_once LOGY_CORE . 'pages/logy-login.php';
        require_once LOGY_CORE . 'pages/logy-register.php';
        require_once LOGY_CORE . 'pages/logy-lost-password.php';

        // Init Classes
        $this->setup          = new Logy_Setup();
        $this->fields         = new Logy_Fields();
        $this->login          = new Logy_Login();
        $this->register       = new Logy_Register();
        $this->form           = new Logy_Form();
        $this->mail           = new Logy_Mail();
        $this->social         = new Logy_Social();
        $this->query          = new Logy_Query();
        $this->limit          = new Logy_Limit();
        $this->rewrite        = new Logy_Rewrite();
        $this->styling        = new Logy_Styling();
        $this->lost_password  = new Logy_Lost_Password();

    }

    /**
     * # Plugin Activation Options .
     */
    public static function plugin_activation() {
        $Logy = new Logy();
        // Build Database.
        $Logy->setup->build_database_tables();
        // Install Options
        $Logy->setup->install_options();
        // Install Pages
        $Logy->setup->install_pages();
    }

    /**
     * # Logy Global Variables .
     */
    private function logy_globals() {

        global $wpdb, $Logy_Translation, $Logy_Settings, $Logy_default_options, $Logy_users_table;

        // Get Data.
        $Logy_Settings = $this->fields;
        $Logy_default_options = $this->setup->standard_options();

        // Get Logy Users Table Name.
        $Logy_users_table = $wpdb->prefix . 'logy_users';
        
        // Translation Data.
        $Logy_Translation = array(
            'try_later'             => __( 'Something went wrong, please try again later.', 'logy' ),
            'reset_error'           => __( 'An Error Occurred While Resetting The Options !!', 'logy' ),
            'reset_dialog_title'    => __( 'Resetting Options Confirmation', 'logy' ),
            'required_fields'       => __( 'all fields are required !', 'logy' ),
            'empty_field'           => __( 'Field Name Is Empty !', 'logy' ),
            'empty_options'         => __( 'Options are empty !', 'logy' ),
            'processing'            => __( 'processing... !', 'logy' ),
            'save_changes'          => __( 'save changes', 'logy' ),
            'error_msg'             => __( 'Ops, Error !', 'logy' ),
            'photo_link'            => __( 'photo link', 'logy' ),
            'success_msg'           => __( "Success !", 'logy' ),
            'edit_item'             => __( 'edit item', 'logy' ),
            'got_it'                => __( 'got it!', 'logy' ),
            'done'                  => __( 'save', 'logy' ),

            // Passing Data.
            'default_img' => LOGY_PA . 'images/default-img.png',
            'ajax_url'    => admin_url( 'admin-ajax.php' )

        );

    }

    /**
     * Text Domain
     */
    function load_textdomain() {
        $domain = 'logy';
        $locale = apply_filters( 'plugin_locale', get_locale(), $domain );
        if ( $loaded = load_textdomain( $domain, trailingslashit( WP_LANG_DIR ) . $domain . '/' . $domain . '-' . $locale . '.mo' ) ) {
            return $loaded;
        } else {
            load_plugin_textdomain( $domain, FALSE, basename( dirname( __FILE__ ) ) . '/languages/' );
        }
    }
}