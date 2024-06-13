<?php
/**
 * Scholarship main admin class
 *
 * @package Scholarship
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Scholarship_Notice' ) ) :

    /**
     * Class Scholarship_Admin_Main
     */
    class Scholarship_Notice {
        
    	public $theme_screenshot;
        public $theme_name;

        /**
         * scholarship Notice constructor.
         */
        public function __construct() {

            global $admin_main_class;

            add_action( 'admin_enqueue_scripts', array( $this, 'scholarship_enqueue_scripts' ) );

            add_action( 'wp_loaded', array( $this, 'scholarship_hide_welcome_notices' ) );
            add_action( 'wp_loaded', array( $this, 'scholarship_welcome_notice' ) ); 

            add_action( 'wp_ajax_scholarship_activate_plugin', array( $admin_main_class, 'scholarship_activate_demo_importer_plugin' ) );
            add_action( 'wp_ajax_scholarship_install_plugin', array( $admin_main_class, 'scholarship_install_demo_importer_plugin' ) );

            add_action( 'wp_ajax_scholarship_install_free_plugin', array( $admin_main_class, 'scholarship_install_free_plugin' ) );
            add_action( 'wp_ajax_scholarship_activate_free_plugin', array( $admin_main_class, 'scholarship_activate_free_plugin' ) );

            //theme details
            $theme = wp_get_theme();

            if ( ! is_child_theme() ) {
                $this->theme_screenshot =  get_template_directory_uri()."/screenshot.png";
            } else {
                $this->theme_screenshot =  get_stylesheet_directory_uri()."/screenshot.png";
            }

            $this->theme_name = $theme->get( 'Name' );
        }

        /**
         * Localize array for import button AJAX request.
         */
        public function scholarship_enqueue_scripts() {
            wp_enqueue_style( 'scholarship-admin-style', get_template_directory_uri() . '/inc/admin/assets/css/admin.css', array(), scholarship_VERSION );

            wp_enqueue_script( 'scholarship-plugin-install-helper', get_template_directory_uri() . '/inc/admin/assets/js/plugin-handle.js', array( 'jquery' ), scholarship_VERSION );

            $demo_importer_plugin = WP_PLUGIN_DIR . '/mysterythemes-demo-importer/mysterythemes-demo-importer.php';
            if ( ! file_exists( $demo_importer_plugin ) ) {
                $action = 'install';
            } elseif ( file_exists( $demo_importer_plugin ) && !is_plugin_active( 'mysterythemes-demo-importer/mysterythemes-demo-importer.php' ) ) {
                $action = 'activate';
            } else {
                $action = 'redirect';
            }

            wp_localize_script( 'scholarship-plugin-install-helper', 'ogAdminObject',
                array(
                    'ajax_url'      => esc_url( admin_url( 'admin-ajax.php' ) ),
                    '_wpnonce'      => wp_create_nonce( 'scholarship_plugin_install_nonce' ),
                    'buttonStatus'  => esc_html( $action )
                )
            );
        }

        /**
         * Add admin welcome notice.
         */
        public function scholarship_welcome_notice() {

            if ( isset( $_GET['activated'] ) ) {
                update_option( 'scholarship_admin_notice_welcome', true );
            }

            $welcome_notice_option = get_option( 'scholarship_admin_notice_welcome' );

            // Let's bail on theme activation.
            if ( $welcome_notice_option ) {
                add_action( 'admin_notices', array( $this, 'scholarship_welcome_notice_html' ) );
            }
        }

        /**
         * Hide a notice if the GET variable is set.
         */
        public static function scholarship_hide_welcome_notices() {
            if ( isset( $_GET['scholarship-hide-welcome-notice'] ) && isset( $_GET['_scholarship_welcome_notice_nonce'] ) ) {
                if ( ! wp_verify_nonce( $_GET['_scholarship_welcome_notice_nonce'], 'scholarship_hide_welcome_notices_nonce' ) ) {
                    wp_die( esc_html__( 'Action failed. Please refresh the page and retry.', 'scholarship' ) );
                }

                if ( ! current_user_can( 'manage_options' ) ) {
                    wp_die( esc_html__( 'Cheat in &#8217; huh?', 'scholarship' ) );
                }

                $hide_notice = sanitize_text_field( $_GET['scholarship-hide-welcome-notice'] );
                update_option( 'scholarship_admin_notice_' . $hide_notice, false );
            }
        }

        /**
         * function to display welcome notice section
         */
        public function scholarship_welcome_notice_html() {
            $current_screen = get_current_screen();

            if ( $current_screen->id !== 'dashboard' && $current_screen->id !== 'themes' ) {
                return;
            }
            ?>
            <div id="scholarship-welcome-notice" class="scholarship-welcome-notice-wrapper updated notice">
                <a class="scholarship-message-close notice-dismiss" href="<?php echo esc_url( wp_nonce_url( remove_query_arg( array( 'activated' ), add_query_arg( 'scholarship-hide-welcome-notice', 'welcome' ) ), 'scholarship_hide_welcome_notices_nonce', '_scholarship_welcome_notice_nonce' ) ); ?>">
                    <span class="screen-reader-text"><?php esc_html_e( 'Dismiss this notice.', 'scholarship' ); ?>
                </a>
                <div class="scholarship-welcome-title-wrapper">
                    <h2 class="notice-title"><?php esc_html_e( 'Congratulations!', 'scholarship' ); ?></h2>
                    <p class="notice-description">
                        <?php
                            printf( esc_html__( '%1$s is now installed and ready to use. Now that you are part of us. We have curated some important links for you to get rolling.', 'scholarship' ), $this->theme_name );
                        ?>
                    </p>
                </div><!-- .scholarship-welcome-title-wrapper -->
                <div class="welcome-notice-details-wrapper">

                    <div class="notice-detail-wrap image">
                        <figure> <img src="<?php echo esc_url( $this->theme_screenshot ); ?>"> </figure>
                    </div><!-- .notice-detail-wrap.image -->

                    <div class="notice-detail-wrap general">
                        <div class="general-info-wrap">
                            <h2 class="general-title wrap-title"><span class="dashicons dashicons-admin-generic"></span><?php esc_html_e( 'General Info', 'scholarship' ); ?></h2>
                            <div class="general-content wrap-content">
                                <?php
                                    printf( wp_kses_post( 'All the amazing features provided by scholarship are now at your disposal. Let them serve you with ease. get started button will process to installation of <b> Mystery Themes Demo Importer</b> Plugin and redirect to the theme settings page. In order to summon scholarship dashboard, click on the Theme Settings buton in below.<br />And Thank so much for being a part of us.', 'scholarship' ) );
                                ?>
                            </div><!-- .wrap-content -->
                        </div><!-- .general-info-wrap -->
                        <div class="general-info-links">
                            <div class="buttons-wrap">
                                <button class="scholarship-get-started button button-primary button-hero" data-done="<?php esc_attr_e( 'Done!', 'scholarship' ); ?>" data-process="<?php esc_attr_e( 'Processing', 'scholarship' ); ?>" data-redirect="<?php echo esc_url( wp_nonce_url( add_query_arg( 'scholarship-hide-welcome-notice', 'welcome', admin_url( 'themes.php' ).'?page=scholarship-dashboard&tab=starter' ) , 'scholarship_hide_welcome_notices_nonce', '_scholarship_welcome_notice_nonce' ) ); ?>">
                                    <?php printf( esc_html__( 'Get started with %1$s', 'scholarship' ), esc_html( $this->theme_name ) ); ?>
                                </button>
                                <a class="button button-hero" href="<?php echo esc_url( wp_customize_url() ); ?>">
                                    <?php esc_html_e( 'Customize your site', 'scholarship' ); ?>
                                </a>
                            </div><!-- .buttons-wrap -->
                            <div class="links-wrap">
                                
                            </div><!-- .links-wrap -->
                        </div><!-- .general-info-links -->
                    </div><!-- .notice-detail-wrap.general -->

                    <div class="notice-detail-wrap resource">
                        <div class="resource-info-wrap">
                            <div class="demo-wrap">
                                <h2 class="demo-title wrap-title"><span class="dashicons dashicons-images-alt2"></span> <?php esc_html_e( 'Demo', 'scholarship' ); ?></h2>
                                <div class="demo-content wrap-content">
                                    <?php
                                        printf( wp_kses_post( 'Wanna catch a glimpse of how <b>%1$s</b> looks like? You can browse from our demos and see the live previews before you get started.', 'scholarship' ), $this->theme_name );
                                    ?>
                                    <a target="_blank" rel="external noopener noreferrer" href="https://mysterythemes.com/wp-themes/scholarship"><span class="screen-reader-text"><?php esc_html_e( 'opens in a new tab', 'scholarship' ); ?></span><svg xmlns="http://www.w3.org/2000/svg" focusable="false" role="img" viewBox="0 0 512 512" width="12" height="12" style="margin-right: 5px;"><path fill="currentColor" d="M432 320H400a16 16 0 0 0-16 16V448H64V128H208a16 16 0 0 0 16-16V80a16 16 0 0 0-16-16H48A48 48 0 0 0 0 112V464a48 48 0 0 0 48 48H400a48 48 0 0 0 48-48V336A16 16 0 0 0 432 320ZM488 0h-128c-21.4 0-32 25.9-17 41l35.7 35.7L135 320.4a24 24 0 0 0 0 34L157.7 377a24 24 0 0 0 34 0L435.3 133.3 471 169c15 15 41 4.5 41-17V24A24 24 0 0 0 488 0Z"></path></svg><?php esc_html_e( 'Stater Sites', 'scholarship' ); ?></a>
                                </div><!-- .demo-content -->
                            </div><!-- .demo-wrap -->

                            <div class="document-wrap">
                                <h2 class="doc-title wrap-title"><span class="dashicons dashicons-format-aside"></span> <?php esc_html_e( 'Documentation', 'scholarship' ); ?></h2>
                                <div class="doc-content wrap-content">
                                    <?php
                                        printf( wp_kses_post( 'Need in-depth details? Fret not, here`s the full documentation on how to use<b> %1$s </b>and its functionality. Let this detailed documentation navigate you through any confusion along the way. <b>Cheers!</b>', 'scholarship' ), $this->theme_name );
                                    ?>
                                    <a target="_blank" rel="external noopener noreferrer" href="https://docs.mysterythemes.com/scholarship"><span class="screen-reader-text"><?php esc_html_e( 'opens in a new tab', 'scholarship' ); ?></span><svg xmlns="http://www.w3.org/2000/svg" focusable="false" role="img" viewBox="0 0 512 512" width="12" height="12" style="margin-right: 5px;"><path fill="currentColor" d="M432 320H400a16 16 0 0 0-16 16V448H64V128H208a16 16 0 0 0 16-16V80a16 16 0 0 0-16-16H48A48 48 0 0 0 0 112V464a48 48 0 0 0 48 48H400a48 48 0 0 0 48-48V336A16 16 0 0 0 432 320ZM488 0h-128c-21.4 0-32 25.9-17 41l35.7 35.7L135 320.4a24 24 0 0 0 0 34L157.7 377a24 24 0 0 0 34 0L435.3 133.3 471 169c15 15 41 4.5 41-17V24A24 24 0 0 0 488 0Z"></path></svg><?php esc_html_e( 'Read full documentation', 'scholarship' ); ?></a>
                                </div><!-- .doc-content -->
                            </div><!-- .document-wrap -->
                        </div><!-- .resource-info-wrap -->
                    </div><!-- .notice-detail-wrap.resource -->

                </div><!-- .welcome-notice-details-wrapper -->
            </div><!-- .scholarship-welcome-notice-wrapper -->
    <?php
        }
    }
    new Scholarship_Notice();

endif;