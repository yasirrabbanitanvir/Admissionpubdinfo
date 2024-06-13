<?php
/**
 * Scholarship Dashboard
 *
 * @package scholarship
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Scholarship_Admin_Dashboard' ) ) :

    /**
     * Class Scholarship_Admin_Main
     */
    class Scholarship_Admin_Dashboard {

    	public $theme_name;
        public $theme_slug;
        public $theme_author_uri;
        public $theme_author_name;
        public $free_plugins;

       
        /**
         * Scholarship_Admin_Dashboard constructor.
         */
        public function __construct() {

            global $admin_main_class;

            add_action( 'admin_menu', array( $this, 'scholarship_admin_menu' ) );

            //theme details
            $theme                      = wp_get_theme();
            $this->theme_name           = $theme->get( 'Name' );
            $this->theme_slug           = $theme->get( 'TextDomain' );
            $this->theme_author_uri     = $theme->get( 'AuthorURI' );
            $this->theme_author_name    = $theme->get( 'Author' );
            $this->free_plugins = $admin_main_class->scholarship_free_plugins_lists();
        }

        /**
         * Add admin menu.
         */
        public function scholarship_admin_menu() {
            add_theme_page( sprintf( esc_html__( '%1$s Dashboard', 'scholarship' ), $this->theme_name ), sprintf( esc_html__( '%1$s Dashboard', 'scholarship' ), $this->theme_name ) , 'edit_theme_options', 'scholarship-dashboard', array( $this, 'scholarship_get_started_screen' ) );
        }

        public function scholarship_get_started_screen() {
             $current_tab = empty( $_GET['tab'] ) ? 'scholarship_welcome' : sanitize_title( $_GET['tab'] );

            // Look for a {$current_tab}_screen method.
            if ( is_callable( array( $this, $current_tab . '_screen' ) ) ) {
                return $this->{ $current_tab . '_screen' }();
            }

            // Fallback to about screen.
            return $this->scholarship_welcome_screen();
        }

        /**
         * Dashboard header
         *
         * @access private
         */
        private function scholarship_dashboard_header() {
            ?>
            <div class="dashboard-header">
                <div class="scholarship-container">
                    <div class="header-top">
                        <h1 class="heading"><?php printf( esc_html__( '%1$s Options', 'scholarship' ), $this->theme_name ); ?></h1>
                        <span class="theme-version"><?php printf( esc_html__( 'Version: %1$s', 'scholarship' ), scholarship_VERSION ); ?></span>
                        <span class="author-link"><?php printf( wp_kses_post( 'By <a href="%1$s" target="_blank">%2$s</a>', 'scholarship' ), $this->theme_author_uri, $this->theme_author_name ); ?></span>
                    </div><!-- .header-top -->
                    <div class="header-nav">
                        <nav class="dashboard-nav">
                            <li>
                                <a class="nav-tab <?php if ( empty( $_GET['tab'] ) && $_GET['page'] == 'scholarship-dashboard' ) echo 'active'; ?>" href="<?php echo esc_url( admin_url( add_query_arg( array( 'page' => 'scholarship-dashboard' ), 'themes.php' ) ) ); ?>">
                                    <span class="dashicons dashicons-admin-appearance"></span> <?php esc_html_e( 'Welcome', 'scholarship' ); ?>
                                </a>
                            </li>
                            <li>
                                <a class="nav-tab <?php if ( isset( $_GET['tab'] ) && $_GET['tab'] == 'scholarship_starter' ) echo 'active'; ?>" href="<?php echo esc_url( admin_url( add_query_arg( array( 'page' => 'scholarship-dashboard', 'tab' => 'scholarship_starter' ), 'themes.php' ) ) ); ?>">
                                    <span class="dashicons dashicons-images-alt2"></span> <?php esc_html_e( 'Stater Sites', 'scholarship' ); ?>
                                </a>
                            </li>
                             <li>
                                <a class="nav-tab <?php if ( isset( $_GET['tab'] ) && $_GET['tab'] == 'scholarship_plugin' ) echo 'active'; ?>" href="<?php echo esc_url( admin_url( add_query_arg( array( 'page' => 'scholarship-dashboard', 'tab' => 'scholarship_plugin' ), 'themes.php' ) ) ); ?>">
                                    <span class="dashicons dashicons-admin-plugins"></span> <?php esc_html_e( 'Plugin', 'scholarship' ); ?>
                                </a>
                            </li>
                            <li>
                                <a class="nav-tab <?php if ( isset( $_GET['tab'] ) && $_GET['tab'] == 'scholarship_free_pro' ) echo 'active'; ?>" href="<?php echo esc_url( admin_url( add_query_arg( array( 'page' => 'scholarship-dashboard', 'tab' => 'scholarship_free_pro' ), 'themes.php' ) ) ); ?>">
                                    <span class="dashicons dashicons-dashboard"></span> <?php esc_html_e( 'Free Vs Pro', 'scholarship' ); ?>
                                </a>
                            </li>
                            <li>
                                <a class="nav-tab <?php if ( isset( $_GET['tab'] ) && $_GET['tab'] == 'scholarship_changelog' ) echo 'active'; ?>" href="<?php echo esc_url( admin_url( add_query_arg( array( 'page' => 'scholarship-dashboard', 'tab' => 'scholarship_changelog' ), 'themes.php' ) ) ); ?>">
                                    <span class="dashicons dashicons-flag"></span> <?php esc_html_e( 'Changelog', 'scholarship' ); ?>
                                </a>
                            </li>
                        </nav>
                        <div class="upgrade-pro">
                            <a href="<?php echo esc_url( 'https://mysterythemes.com/wp-themes/scholarship-pro' ); ?>" target="_blank" class="button button-primary"><?php esc_html_e( 'More Features With Pro', 'scholarship' ); ?></a>
                        </div><!-- .upgrade-pro -->
                    </div><!-- .header-nav -->
                </div><!-- .scholarship-container -->
            </div><!-- .dashboard-header -->
        <?php
            }


        /**
         * Dashboard sidebar
         * 
         * @access private
         */
        private function scholarship_dashboard_sidebar() {

            $review_url = 'https://wordpress.org/support/theme/'. $this->theme_slug .'/reviews/?filter=5#new-post';

        ?>
            <div class="sidebar-wrapper">
                <aside class="sidebar">
                    <div class="sidebar-block">
                        <h4 class="block-title"><?php esc_html_e( 'Leave us a reivew', 'scholarship' ); ?></h4>
                        <p><?php printf( wp_kses_post( 'Are you are enjoying <b>%1$s</b>? We would love to hear your feedback.', 'scholarship' ), $this->theme_name ); ?></p>
                        <a class="button button-primary" href="<?php echo esc_url( $review_url ); ?>" target="_blank" rel="external noopener noreferrer">
                            <?php esc_html_e( 'Submit a review', 'scholarship' ); ?>
                            <span class="dashicons dashicons-external"></span>
                        </a>
                    </div><!-- .sidebar-block -->
                </aside>
            </div><!-- .sidebar-wrapper -->
            <?php
            }

        /**
         * render the welcome screen.
         */
        public function scholarship_welcome_screen() {
            $doc_url        = 'https://docs.mysterythemes.com/scholarship';
            $support_url    = 'https://wordpress.org/support/theme/'.$this->theme_slug;
            ?>
            <div id="scholarship-dashboard">
                <?php $this->scholarship_dashboard_header(); ?>
                <div class="dashboard-content-wrapper">
                    <div class="scholarship-container">
                        
                        <div class="main-content welcome-content-wrapper">
                            
                            <div class="welcome-block quick-links">
                                <div class="block-header">
                                    <img class="block-icon" src="<?php echo esc_url( get_template_directory_uri() . '/inc/admin/assets/img/quick-link.svg' ); ?>" alt="icon">
                                    <h3 class="block-title"><?php esc_html_e( 'Customizer quick link', 'scholarship' ); ?></h3>
                                </div><!-- .block-header -->
                                <div class="block-content content-column">
                                    <div class="col">    
                                    <li>
                                        <a href="<?php echo esc_url( admin_url( 'customize.php' ).'?autofocus[section]=title_tagline' ); ?>" target="_blank" class="welcome-icon"><span class="dashicons dashicons-visibility"></span><?php esc_html_e( 'Setup site logo', 'scholarship' ); ?></a>
                                    </li>
                                    <li>
                                        <a href="<?php echo esc_url( admin_url( 'customize.php' ).'?autofocus[panel]=scholarship_design_settings_panel' ); ?>" target="_blank" class="welcome-icon"> <span class="dashicons dashicons-text-page"> </span><?php esc_html_e( 'Manage Inner Page', 'scholarship' ); ?></a>
                                    </li>
                                    <li>
                                        <a href="<?php echo esc_url( admin_url( 'customize.php' ).'?autofocus[section]=social_icons_section' ); ?>" target="_blank" class="welcome-icon "><span class="dashicons dashicons-networking"> </span><?php esc_html_e( 'Manage Social Icons', 'scholarship' ); ?></a>
                                    </li>
                                </div>
                                <div class="col">
                                        <li>
                                            <a href="<?php echo esc_url( admin_url( 'nav-menus.php' ) ); ?>" target="_blank" class="welcome-icon "><span class="dashicons dashicons-menu-alt"> </span><?php esc_html_e( 'Manage menus', 'scholarship' ); ?></a>
                                        </li>
                                        <li>
                                            <a href="<?php echo esc_url( admin_url( 'widgets.php' ) ); ?>" target="_blank" class="welcome-icon "><span class="dashicons dashicons-menu-alt"> </span><?php esc_html_e( 'Manage widgets', 'scholarship' ); ?></a>
                                        </li>
                                    </div>
                                </div><!-- .block-content -->
                            </div><!-- .welcome-block.quick-links -->

                            <div class="welcome-block documentation">
                                <div class="block-header">
                                    <img class="block-icon" src="<?php echo esc_url( get_template_directory_uri() . '/inc/admin/assets/img/docs.svg' ); ?>" alt="icon">
                                    <h3 class="block-title"><?php esc_html_e( 'Theme Documentation', 'scholarship' ); ?></h3>
                                </div><!-- .block-header -->
                                <div class="block-content">
                                    <p>
                                        <?php printf( wp_kses_post( 'Need more details? Please check our full documentation for detailed information on how to use <b>%1$s</b>.', 'scholarship' ), $this->theme_name ); ?>
                                        <a href="<?php echo esc_url( $doc_url ); ?>" target="_blank"><?php esc_html_e( 'Go to doc', 'scholarship' ); ?><span class="dashicons dashicons-external"></span></a>
                                    </p>
                                </div><!-- .block-content -->
                            </div><!-- .welcome-block documentation -->

                            <div class="welcome-block support">
                                <div class="block-header">
                                    <img class="block-icon" src="<?php echo esc_url( get_template_directory_uri() . '/inc/admin/assets/img/support.svg' ); ?>" alt="icon">
                                    <h3 class="block-title"><?php esc_html_e( 'Contact Support', 'scholarship' ); ?></h3>
                                </div><!-- .block-header -->
                                <div class="block-content">
                                    <p>
                                        <?php printf( wp_kses_post( 'We want to make sure you have the best experience using <b>%1$s</b>, and that is why we have gathered all the necessary information here for you. We hope you will enjoy using <b>%1$s</b> as much as we enjoy creating great products.', 'scholarship' ), $this->theme_name ); ?>
                                        <a href="<?php echo esc_url( $support_url ); ?>" target="_blank"><?php esc_html_e( 'Contact Support', 'scholarship' ); ?><span class="dashicons dashicons-external"></span></a>
                                    </p>
                                </div><!-- .block-content -->
                            </div><!-- .welcome-block support -->

                            <div class="welcome-block tutorial">
                                <div class="block-header">
                                    <img class="block-icon" src="<?php echo esc_url( get_template_directory_uri() . '/inc/admin/assets/img/tutorial.svg' ); ?>" alt="icon">
                                    <h3 class="block-title"><?php esc_html_e( 'Tutorial', 'scholarship' ); ?></h3>
                                </div><!-- .block-header -->
                                <div class="block-content">
                                    <p>
                                        <?php printf( wp_kses_post( 'This tutorial has been prepared for those who have a basic knowledge of HTML and CSS and has an urge to develop websites. After completing this tutorial, you will find yourself at a moderate level of expertise in developing sites or blogs using WordPress.', 'scholarship' ), $this->theme_name ); ?>
                                        <a href="<?php echo esc_url( 'https://wpallresources.com/' ); ?>" target="_blank"><?php esc_html_e( 'WP Tutorials', 'scholarship' ); ?><span class="dashicons dashicons-external"></span></a>
                                    </p>
                                </div><!-- .block-content -->
                            </div><!-- .welcome-block tutorial -->

                            <div class="return-to-dashboard">
                                <?php if ( current_user_can( 'update_core' ) && isset( $_GET['updated'] ) ) : ?>
                                    <a href="<?php echo esc_url( self_admin_url( 'update-core.php' ) ); ?>">
                                        <?php is_multisite() ? esc_html_e( 'Return to Updates', 'scholarship' ) : esc_html_e( 'Return to Dashboard &rarr; Updates', 'scholarship' ); ?>
                                    </a> |
                                <?php endif; ?>
                                <a href="<?php echo esc_url( self_admin_url() ); ?>"><?php is_blog_admin() ? esc_html_e( 'Go to Dashboard &rarr; Home', 'scholarship' ) : esc_html_e( 'Go to Dashboard', 'scholarship' ); ?></a>
                            </div><!-- .return-to-dashboard -->

                        </div><!-- .welcome-content-wrapper -->

                        <?php $this->scholarship_dashboard_sidebar(); ?>

                    </div><!-- .scholarship-container -->
                </div><!-- .dashboard-content-wrapper -->
            </div><!-- #scholarship-dashboard -->
        <?php
        }

        /**
         * render the starter sites screen
         */
        public function scholarship_starter_screen() {
            global $admin_main_class;

            $is_child_theme     = is_child_theme();
            $activated_theme    = get_template();
            $parent_theme       = get_template();

            $demodata           = get_transient( 'scholarship_demo_packages' );
            
            if ( empty( $demodata ) || $demodata == false ) {
                $demodata = get_transient( 'mtdi_theme_packages' );
                if ( $demodata ) {
                    set_transient( 'scholarship_demo_packages', $demodata, WEEK_IN_SECONDS );
                }
            }

            $activated_demo_check   = get_option( 'mtdi_activated_check' );

            ?>
            <div id="scholarship-dashboard">
                <?php $this->scholarship_dashboard_header(); ?>
                <div class="dashboard-content-wrapper starter-dashboard-content-wrapper">
                    <div class="scholarship-container">

                        <div class="main-content starter-content-wrapper">
                            <div class="scholarship-theme-demos rendered <?php if( isset( $demodata ) && empty( $demodata ) ) echo "no-demo-sites" ?>">
                                <?php 
                                $admin_main_class->scholarship_install_demo_import_plugin_popup(); 
                                ?>
                                <div class="demo-listing-wrapper wp-clearfix">
                                    <?php if ( isset( $demodata ) && empty( $demodata ) ) { ?>
                                        <span class="configure-msg"><?php esc_html_e( 'No demos are configured for this theme, please contact the theme author', 'scholarship' ); ?></span>
                                    <?php
                                        } else {
                                    ?>
                                        <div class="all-demos-wrap">
                                            <?php

                                                // List child theme demo if exists
                                                if ( $is_child_theme ) {
                                                    $is_child_exists = ( isset( $demodata[$parent_theme]['is_child_exists'] ) ) ? $demodata[$parent_theme]['is_child_exists'] : false;
                                                    if ( isset( $is_child_exists ) && $is_child_exists ) {
                                                        $child_theme_demodatas  = $demodata[$parent_theme]['child_themes'];
                                                        foreach( $child_theme_demodatas as $child_theme_value ) {
                                                            $theme_name         = $child_theme_value['name'];
                                                            $theme_slug         = $child_theme_value['theme_slug'];
                                                            $preview_screenshot = $child_theme_value['preview_screen'];
                                                            $demourl            = $child_theme_value['preview_url'];
                                                            if ( ( strpos( $activated_theme, 'pro' ) !== false && strpos( $theme_slug, 'pro' ) !== false ) || ( strpos( $activated_theme, 'pro' ) == false ) ) {
                                                        ?>
                                                                <div class="single-demo<?php if  ( strpos( $activated_theme, 'pro' ) == false && strpos( $theme_slug, 'pro' ) !== false ) { echo ' pro-demo'; } ?>" data-categories="ltrdemo" data-name="<?php echo esc_attr ( $theme_slug ); ?>" style="display: block;">
                                                                    <div class="preview-screenshot">
                                                                        <a href="<?php echo esc_url ( $demourl ); ?>" target="_blank">
                                                                            <img class="preview" src="<?php echo esc_url ( $preview_screenshot ); ?>" />
                                                                        </a>
                                                                    </div><!-- .preview-screenshot -->
                                                                    <div class="demo-info-wrapper">
                                                                        <h2 class="mtdi-theme-name theme-name" id="nokri-name"><?php echo esc_html ( $theme_name ); ?></h2>
                                                                        <div class="mtdi-theme-actions theme-actions">
                                                                            <?php
                                                                                if ( $activated_demo_check != '' && $activated_demo_check == $theme_slug ) {
                                                                            ?>
                                                                                    <a class="button disabled button-primary hide-if-no-js" href="javascript:void(0);" data-name="<?php echo esc_attr ( $theme_name ); ?>" data-slug="<?php echo esc_attr ( $theme_slug ); ?>" aria-label="<?php printf ( esc_html__( 'Imported %1$s', 'scholarship' ), $theme_name ); ?>">
                                                                                        <?php esc_html_e( 'Imported', 'scholarship' ); ?>
                                                                                    </a>
                                                                            <?php
                                                                                } else {
                                                                                    if ( is_plugin_active( 'mysterythemes-demo-importer/mysterythemes-demo-importer.php' ) ) {
                                                                                        $button_tooltip = esc_html__( 'Click to import demo', 'scholarship' );
                                                                                    } else {
                                                                                        $button_tooltip = esc_html__( 'Demo importer plugin is not installed or activated', 'scholarship' );
                                                                                    }
                                                                            ?>
                                                                                    <a title="<?php echo esc_attr( $button_tooltip ); ?>" class="button button-primary hide-if-no-js mtdi-demo-import" href="javascript:void(0);" data-name="<?php echo esc_attr ( $theme_name ); ?>" data-slug="<?php echo esc_attr ( $theme_slug ); ?>" aria-label="<?php printf ( esc_attr__( 'Import %1$s', 'scholarship' ), $theme_name ); ?>">
                                                                                        <?php esc_html_e( 'Import', 'scholarship' ); ?>
                                                                                    </a>
                                                                            <?php
                                                                                }
                                                                            ?>
                                                                            <a class="button preview install-demo-preview" target="_blank" href="<?php echo esc_url ( $demourl ); ?>">
                                                                                <?php esc_html_e( 'View Demo', 'scholarship' ); ?>
                                                                            </a>
                                                                        </div><!-- .mtdi-theme-actions -->
                                                                    </div><!-- .demo-info-wrapper -->
                                                                </div><!-- .single-demo -->
                                                        <?php
                                                            }
                                                        }
                                                    }
                                                } // Endif ( $is_child_theme )

                                                foreach ( $demodata as $value ) {
                                                    $theme_name         = $value['name'];
                                                    $theme_slug         = $value['theme_slug'];
                                                    $preview_screenshot = $value['preview_screen'];
                                                    $demourl            = $value['preview_url'];
                                                    if ( ( strpos( $activated_theme, 'pro' ) !== false && strpos( $theme_slug, 'pro' ) !== false ) || ( strpos( $activated_theme, 'pro' ) == false ) ) {
                                            ?>
                                                        <div class="single-demo<?php if ( strpos( $activated_theme, 'pro' ) == false && strpos( $theme_slug, 'pro' ) !== false ) { echo ' pro-demo'; } ?>" data-categories="ltrdemo" data-name="<?php echo esc_attr ( $theme_slug ); ?>" style="display: block;">
                                                            <div class="preview-screenshot">
                                                                <a href="<?php echo esc_url ( $demourl ); ?>" target="_blank">
                                                                    <img class="preview" src="<?php echo esc_url ( $preview_screenshot ); ?>" />
                                                                </a>
                                                            </div><!-- .preview-screenshot -->
                                                            <div class="demo-info-wrapper">
                                                                <h2 class="mtdi-theme-name theme-name" id="nokri-name"><?php echo esc_html ( $theme_name ); ?></h2>
                                                                <div class="mtdi-theme-actions theme-actions">
                                                                    <?php
                                                                        if ( $activated_demo_check != '' && $activated_demo_check == $theme_slug ) {
                                                                    ?>
                                                                            <a class="button disabled button-primary hide-if-no-js" href="javascript:void(0);" data-name="<?php echo esc_attr ( $theme_name ); ?>" data-slug="<?php echo esc_attr ( $theme_slug ); ?>" aria-label="<?php printf ( esc_html__( 'Imported %1$s', 'scholarship' ), $theme_name ); ?>">
                                                                                <?php esc_html_e( 'Imported', 'scholarship' ); ?>
                                                                            </a>
                                                                    <?php
                                                                        } else {
                                                                            if ( strpos( $activated_theme, 'pro' ) == false && strpos( $theme_slug, 'pro' ) !== false ) {
                                                                                $s_slug = explode( "-pro", $theme_slug );
                                                                                $purchaseurl = 'https://mysterythemes.com/wp-themes/'.$s_slug[0].'-pro';
                                                                    ?>
                                                                                <a class="button button-primary mtdi-purchasenow" href="<?php echo esc_url( $purchaseurl ); ?>" target="_blank" data-name="<?php echo esc_attr ( $theme_name ); ?>" data-slug="<?php echo esc_attr ( $theme_slug ); ?>" aria-label="<?php printf ( esc_html__( 'Purchase Now', 'scholarship' ), $theme_name ); ?>">
                                                                                    <?php esc_html_e( 'Buy Now', 'scholarship' ); ?>
                                                                                </a>
                                                                    <?php
                                                                            } else {
                                                                                if ( is_plugin_active( 'mysterythemes-demo-importer/mysterythemes-demo-importer.php' ) ) {
                                                                                    $button_tooltip = esc_html__( 'Click to import demo', 'scholarship' );
                                                                                } else {
                                                                                    $button_tooltip = esc_html__( 'Demo importer plugin is not installed or activated', 'scholarship' );
                                                                                }
                                                                    ?>
                                                                                <a title="<?php echo esc_attr( $button_tooltip ); ?>" class="button button-primary hide-if-no-js mtdi-demo-import" href="javascript:void(0);" data-name="<?php echo esc_attr ( $theme_name ); ?>" data-slug="<?php echo esc_attr ( $theme_slug ); ?>" aria-label="<?php printf ( esc_attr__( 'Import %1$s', 'scholarship' ), $theme_name ); ?>">
                                                                                    <?php esc_html_e( 'Import', 'scholarship' ); ?>
                                                                                </a>
                                                                    <?php
                                                                            }
                                                                        }
                                                                    ?>
                                                                        <a class="button preview install-demo-preview" target="_blank" href="<?php echo esc_url ( $demourl ); ?>">
                                                                            <?php esc_html_e( 'View Demo', 'scholarship' ); ?>
                                                                        </a>
                                                                </div><!-- .mtdi-theme-actions -->
                                                            </div><!-- .demo-info-wrapper -->
                                                        </div><!-- .single-demo -->
                                            <?php
                                                    }
                                                }
                                            ?>
                                        </div><!-- .mtdi-demo-wrapper -->
                                <?php
                                    }
                                ?>
                                </div><!-- .demo-listing-wrapper -->

                            </div><!-- .scholarship-theme-demos -->

                        </div><!-- .starter-content-wrapper -->
                    </div><!-- .scholarship-container -->
                </div><!-- .dashboard-content-wrapper -->
            </div><!-- #scholarship-dashboard -->
    <?php
        }

        /**
         * render the free vs pro screen
         */
        public function scholarship_free_pro_screen() {
    ?>
            <div id="scholarship-dashboard">
                <?php $this->scholarship_dashboard_header(); ?>
                <div class="dashboard-content-wrapper">
                    <div class="scholarship-container">

                        <div class="main-content free-pro-content-wrapper">
                            
                            <table class="compare-table">
                                <thead>
                                    <tr>
                                        <th class="table-feature-title"><h3><?php esc_html_e( 'Features', 'scholarship' ); ?></h3></th>
                                        <th><h3><?php echo esc_html( $this->theme_name ); ?></h3></th>
                                        <th><h3><?php esc_html_e( 'Scholarship Pro', 'scholarship' ); ?></h3></th>
                                    </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td><h3><?php esc_html_e( 'Price', 'scholarship' ); ?></h3></td>
                                    <td><?php esc_html_e( 'Free', 'scholarship' ); ?></td>
                                    <td><?php esc_html_e( '$59.99', 'scholarship' ); ?></td>
                                </tr>
                                <tr>
                                    <td><h3><?php esc_html_e( 'Import Demo Data', 'scholarship' ); ?></h3></td>
                                    <td><span class="dashicons dashicons-yes"></span></td>
                                    <td><span class="dashicons dashicons-yes"></span></td>
                                </tr>
                                 <tr>
                                    <td><h3><?php esc_html_e( 'Pre Loaders Layouts', 'scholarship' ); ?></h3></td>
                                    <td><span class="dashicons dashicons-no-alt"></span></td>
                                    <td><span class="dashicons dashicons-yes"></span></td>
                                </tr>
                                 <tr>
                                    <td><h3><?php esc_html_e( 'Header Layouts', 'scholarship' ); ?></h3></td>
                                    <td><?php esc_html_e( '1', 'scholarship' ); ?></td>
                                    <td><?php esc_html_e( '3', 'scholarship' ); ?></td>
                                </tr>
                                 <tr>
                                    <td><h3><?php esc_html_e( 'Archieve Page  Layouts', 'scholarship' ); ?></h3></td>
                                    <td><?php esc_html_e( '1', 'scholarship' ); ?></td>
                                    <td><?php esc_html_e( '3', 'scholarship' ); ?></td>
                                </tr>
                                <tr>
                                    <td><h3><?php esc_html_e( 'Single Page  Layouts', 'scholarship' ); ?></h3></td>
                                    <td><?php esc_html_e( '1', 'scholarship' ); ?></td>
                                    <td><?php esc_html_e( '3', 'scholarship' ); ?></td>
                                </tr>
                                <tr>
                                    <td><h3><?php esc_html_e( 'Google Fonts', 'scholarship' ); ?></h3></td>
                                    <td><span class="dashicons dashicons-no-alt"></span></td>
                                    <td><?php esc_html_e( '600+', 'scholarship' ); ?></td>
                                </tr>
                                <tr>
                                    <td><h3><?php esc_html_e( 'Typography Options', 'scholarship' ); ?></h3></td>
                                    <td><span class="dashicons dashicons-no-alt"></span></td>
                                    <td><span class="dashicons dashicons-yes"></span></td>
                                </tr>
                                 <tr>
                                    <td><h3><?php esc_html_e( 'WooCommerce Plugin Compatible', 'scholarship' ); ?></h3></td>
                                    <td><span class="dashicons dashicons-no-alt"></span></td>
                                    <td><span class="dashicons dashicons-yes"></span></td>
                                </tr>
                                  <tr>
                                    <td><h3><?php esc_html_e( 'The Events Calender Plugin Compatible', 'scholarship' ); ?></h3></td>
                                    <td><span class="dashicons dashicons-no-alt"></span></td>
                                    <td><span class="dashicons dashicons-yes"></span></td>
                                </tr>
                                 <tr>
                                    <td><h3><?php esc_html_e( 'No. of Widgets', 'scholarship' ); ?></h3></td>
                                    <td><?php esc_html_e( '7', 'scholarship' ); ?></td>
                                    <td><?php esc_html_e( '10', 'scholarship' ); ?></td>
                                </tr>
                                <tr>
                                    <td><h3><?php esc_html_e( 'Custom 404 Page', 'scholarship' ); ?></h3></td>
                                    <td><span class="dashicons dashicons-no-alt"></span></td>
                                    <td><span class="dashicons dashicons-yes"></span></td>
                                </tr>

                                <tr>
                                    <td><h3><?php esc_html_e( 'Animation on Section', 'scholarship' ); ?></h3></td>
                                    <td><span class="dashicons dashicons-no-alt"></span></td>
                                    <td><span class="dashicons dashicons-yes"></span></td>
                                </tr>
                                 <tr>
                                    <td><h3><?php esc_html_e( 'Bottom Footer Layout', 'scholarship' ); ?></h3></td>
                                    <td><?php esc_html_e( '1', 'scholarship' ); ?></td>
                                    <td><?php esc_html_e( '2', 'scholarship' ); ?></td>
                                </tr>
                                <tr>
                                    <td><?php esc_html_e( 'Get access to all Pro features and power-up your website', 'scholarship' ); ?></td>
                                    <td></td>
                                    <td class="btn-wrapper">
                                    <a href="<?php echo esc_url( apply_filters( 'scholarship_pro_theme_url', 'https://mysterythemes.com/wp-themes/scholarship-pro' ) ); ?>" class="button button-primary" target="_blank"><?php esc_html_e( 'Buy Pro', 'scholarship' ); ?></a>
                                    </td>
                                </tr>
                            </tbody>
                            </table>

                        </div><!-- .free-pro-content-wrapper -->

                        <?php $this->scholarship_dashboard_sidebar(); ?>

                    </div><!-- .scholarship-container -->
                </div><!-- .dashboard-content-wrapper -->
            </div><!-- #scholarship-dashboard -->
    <?php
        }
        
        /**
         * render the changelog screen
         */
        public function scholarship_changelog_screen() {

            global $admin_main_class;

            if ( ! is_child_theme() ) {
                $changelogFilePath = get_template_directory() . '/changelog.txt';
            } else {
                $changelogFilePath = get_stylesheet_directory() . '/changelog.txt';
            }

            $get_changelog = $admin_main_class->get_changelog( $changelogFilePath );

            ?>
            <div id="scholarship-dashboard">
                <?php $this->scholarship_dashboard_header(); ?>
                <div class="dashboard-content-wrapper">
                    <div class="changelog-top-wrapper">
                        <ul class="scholarship-container">
                            <li>
                                <span class="new"><?php esc_html_e( 'N', 'scholarship' ); ?></span>
                                <?php esc_html_e( 'New', 'scholarship' ); ?>
                            </li>
                            <li>
                                <span class="improvement"><?php esc_html_e( 'I', 'scholarship' ); ?></span>
                                <?php esc_html_e( 'Improvement', 'scholarship' ); ?>
                            </li>
                            <li>
                                <span class="fixed"><?php esc_html_e( 'F', 'scholarship' ); ?></span>
                                <?php esc_html_e( 'Fixed', 'scholarship' ); ?>
                            </li>
                            <li>
                                <span class="tweak"><?php esc_html_e( 'T', 'scholarship' ); ?></span>
                                <?php esc_html_e( 'Tweak', 'scholarship' ); ?>
                            </li>
                        </ul>
                    </div><!-- .changelog-top-wrapper -->
                    <div class="scholarship-container">
                        <div class="changelog-content-wrapper">
                            <?php
                                foreach( $get_changelog as $log ) {
                            ?>
                                    <section class="changelog-block">
                                        <div class="block-top">
                                            <span class="block-version"><?php printf( esc_html__( 'Version: %1$s', 'scholarship' ), $log['version'] ); ?></span>
                                            <span class="block-date"><?php printf( esc_html__( 'Released on: %1$s', 'scholarship' ), $log['date'] ); ?></span>
                                        </div><!-- .block-top -->
                                        <div class="block-content">
                                            <ul>
                                                <?php
                                                    // loop for new 
                                                    if ( ! empty( $log['new'] ) ) {
                                                        foreach( $log['new'] as $note ) {
                                                            echo '<li><span class="new" title="New">N</span>'. esc_html( $note ) .'</li>';
                                                        }
                                                    }
                                                    
                                                    // loop for improvement
                                                    if ( ! empty( $log['imp'] ) ) {
                                                        foreach( $log['imp'] as $note ) {
                                                            echo '<li><span class="improvement" title="Improvement">I</span>'. esc_html( $note ) .'</li>';
                                                        }
                                                    }

                                                    // loop for fixed
                                                    if ( ! empty( $log['fix'] ) ) {
                                                        foreach( $log['fix'] as $note ) {
                                                            echo '<li><span class="fixed" title="Fixed">F</span>'. esc_html( $note ) .'</li>';
                                                        }
                                                    }

                                                    // loop for tweak
                                                    if ( ! empty( $log['tweak'] ) ) {
                                                        foreach( $log['tweak'] as $note ) {
                                                            echo '<li><span class="tweak" title="Tweak">T</span>'. esc_html( $note ) .'</li>';
                                                        }
                                                    }
                                                ?>
                                            </ul>
                                        </div><!-- .block-content -->
                                    </section><!-- .changelog-block -->
                            <?php
                                }
                            ?>
                        </div><!-- .changelog-content-wrapper -->

                        <?php $this->scholarship_dashboard_sidebar(); ?>

                    </div><!-- .scholarship-container -->
                </div><!-- .dashboard-content-wrapper -->
            </div><!-- #scholarship-dashboard -->
        <?php
        }

        /**
         * render the plugin screen
         */
        public function scholarship_plugin_screen() {

            global $admin_main_class;

            $free_plugins = $this->free_plugins;
            ?>
            <div id="scholarship-dashboard">
                <?php $this->scholarship_dashboard_header(); ?>
                <div class="dashboard-content-wrapper">
                    <div class="scholarship-container">

                        <div class="plugin-content-wrapper">
                            <div class="header-content">
                                <h3><?php esc_html_e( 'Recommended Free Plugins', 'scholarship' ); ?></h3>
                                <p><?php esc_html_e( 'These Free Plugins might be handy for you.', 'scholarship' ); ?></p>
                            </div><!-- .header-content -->
                            <div class="plugin-listing">
                                <?php
                                    foreach( $free_plugins as $key => $value ) {

                                        switch( $value['action'] ) {
                                            case 'install' :
                                                $btn_class  = 'mt-action-btn install-online button';
                                                $label      = esc_html__( 'Install and Activate', 'scholarship' );
                                                break;

                                            case 'inactive' :
                                                $btn_class  = 'mt-action-btn deactivate-online button disabled';
                                                $label      = esc_html__( 'Deactivate', 'scholarship' );
                                                break;

                                            case 'active' :
                                                $btn_class  = 'mt-action-btn activate-online button button-primary';
                                                $label      = esc_html__( 'Activate', 'scholarship' );
                                                break;
                                        }
                                ?>
                                        <div class="single-plugin-wrap">
                                            <div class="plugin-thumb-wrap">
                                                <div class="plugin-thumb">
                                                    <?php
                                                        if ( ! empty( $value['icon_url'] ) ) {
                                                            echo '<img src="'. esc_url( $value['icon_url'] ) .'" />';
                                                        }
                                                    ?>
                                                </div>
                                            </div><!-- .plugin-thumb-wrap -->
                                            <div class="plugin-content-wrap">
                                                <h4 class="plugin-name"><?php echo esc_html( $value['name'] ); ?></h4>
                                                <div class="plugin-meta-wrap">
                                                    <span class="version"><?php printf( esc_html__( 'Version %1$s', 'scholarship' ), $value['version'] ); ?></span>
                                                    <span class="seperator">|</span>
                                                    <span class="author"><?php echo wp_kses_post( $value['author'] ); ?></span><br>
                                                    <span class="description"><?php echo wp_kses_post( $value['description'] ); ?></span>

                                                </div><!-- .plugin-meta-wrap -->
                                                <div class="plugin-button-wrap plugin-card-<?php echo esc_attr( $value['slug'] ); ?>">
                                                    <button class="<?php echo esc_attr( $btn_class ); ?>" data-status = <?php echo esc_attr( $value['action'] ); ?> data-slug="<?php echo esc_attr( $value['slug'] ); ?>" data-redirect="<?php echo esc_url( admin_url( 'themes.php' ).'?page=scholarship-dashboard&tab=scholarship_plugin' ); ?>"><?php echo esc_html( $label ); ?></button>
                                                </div><!-- .plugin-button-wrap -->
                                            </div><!-- .plugin-content-wrap -->
                                        </div><!-- .single-plugin-wrap -->
                                <?php
                                    }
                                ?>
                            </div><!-- .plugin-listing -->
                        </div><!-- .plugin-content-wrapper -->

                        <?php $this->scholarship_dashboard_sidebar(); ?>

                    </div><!-- .scholarship-container -->
                </div><!-- .dashboard-content-wrapper -->
            </div><!-- #scholarship-dashboard -->
        <?php
        }
    }

    new Scholarship_Admin_Dashboard();

endif;
