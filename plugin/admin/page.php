<?php
/**
 * @package Page Métrics
 * @version 1.5.1
 */
defined('ABSPATH') or exit();

include_once plugin_dir_path(__FILE__) . 'form.php';

class slwsu_page_metrics_admin_page {

    /**
     *
     */
    public function __construct() {
        $this->_init();
    }

    /**
     *
     */
    private function _init() {
        add_action('admin_menu', array($this, 'admin_menu'));
        add_action('admin_init', array($this, 'admin_settings'));
        add_action('admin_head', array($this, 'admin_css'));
    }

    /**
     * ...
     */
    public function admin_menu() {
        global $GROUPER_PAGE_METRICS;
        if (is_object($GROUPER_PAGE_METRICS)):
            // Grouper
            $GROUPER_PAGE_METRICS->add_admin_menu();
            add_submenu_page($GROUPER_PAGE_METRICS->grp_id, 'Page Métrics', 'Page Métrics', 'manage_options', 'page-metrics', array($this, 'admin_page'));
        else:
            add_menu_page('Page Métrics', 'Page Métrics', 'activate_plugins', 'page-metrics', array($this, 'admin_page'));
        endif;
    }

    /**
     * ...
     */
    public function admin_page() {
        ?>
        <div class="wrap">
            <?php
            slwsu_page_metrics_admin_form::action();
            echo '<h1>Page Métrics</h1>';
            slwsu_page_metrics_admin_form::validation();
            slwsu_page_metrics_admin_form::message($_POST);
            ?>
            <form method="post" action="options.php">
                <?php
                do_settings_sections('slwsu_page_metrics_options');
                settings_fields('slwsu_page_metrics_settings');
                submit_button();
                ?>
            </form>
        </div>
        <?php
    }

    /**
     *
     */
    public function admin_settings() {
        // Section plugin
        add_settings_section(
                'slwsu_page_metrics_section_plugin', __('General', 'pmtx'), array($this, 'section_plugin'), 'slwsu_page_metrics_options'
        );
        //
        add_settings_field(
                'slwsu_page_metrics_activation', __('Activate', 'pmtx'), array($this, 'display_metrics_activation'), 'slwsu_page_metrics_options', 'slwsu_page_metrics_section_plugin'
        );

        register_setting(
                'slwsu_page_metrics_settings', 'slwsu_page_metrics_activation'
        );
        //
        add_settings_field(
                'slwsu_page_metrics_fps', __('FPS Display', 'pmtx'), array($this, 'display_metrics_fps'), 'slwsu_page_metrics_options', 'slwsu_page_metrics_section_plugin'
        );

        register_setting(
                'slwsu_page_metrics_settings', 'slwsu_page_metrics_fps'
        );
        //
        add_settings_field(
                'slwsu_page_metrics_warnThreshold', __('Warn threshold', 'pmtx'), array($this, 'display_metrics_warnThreshold'), 'slwsu_page_metrics_options', 'slwsu_page_metrics_section_plugin'
        );

        register_setting(
                'slwsu_page_metrics_settings', 'slwsu_page_metrics_warnThreshold'
        );
        //
        add_settings_field(
                'slwsu_page_metrics_ttfb', __('Time to first bit', 'pmtx'), array($this, 'display_metrics_ttfb'), 'slwsu_page_metrics_options', 'slwsu_page_metrics_section_plugin'
        );

        register_setting(
                'slwsu_page_metrics_settings', 'slwsu_page_metrics_ttfb'
        );
        //
        add_settings_field(
                'slwsu_page_metrics_domInteractive', __('Dom interactive', 'pmtx'), array($this, 'display_metrics_domInteractive'), 'slwsu_page_metrics_options', 'slwsu_page_metrics_section_plugin'
        );

        register_setting(
                'slwsu_page_metrics_settings', 'slwsu_page_metrics_domInteractive'
        );
        //
        add_settings_field(
                'slwsu_page_metrics_domComplete', __('Dom complete', 'pmtx'), array($this, 'display_metrics_domComplete'), 'slwsu_page_metrics_options', 'slwsu_page_metrics_section_plugin'
        );

        register_setting(
                'slwsu_page_metrics_settings', 'slwsu_page_metrics_domComplete'
        );
        //
        add_settings_field(
                'slwsu_page_metrics_firstPaint', __('First paint', 'pmtx'), array($this, 'display_metrics_firstPaint'), 'slwsu_page_metrics_options', 'slwsu_page_metrics_section_plugin'
        );

        register_setting(
                'slwsu_page_metrics_settings', 'slwsu_page_metrics_firstPaint'
        );
        //
        add_settings_field(
                'slwsu_page_metrics_pageLoad', __('Page load', 'pmtx'), array($this, 'display_metrics_pageLoad'), 'slwsu_page_metrics_options', 'slwsu_page_metrics_section_plugin'
        );

        register_setting(
                'slwsu_page_metrics_settings', 'slwsu_page_metrics_pageLoad'
        );
        //
        add_settings_field(
                'slwsu_page_metrics_requests', __('Requests', 'pmtx'), array($this, 'display_metrics_requests'), 'slwsu_page_metrics_options', 'slwsu_page_metrics_section_plugin'
        );

        register_setting(
                'slwsu_page_metrics_settings', 'slwsu_page_metrics_requests'
        );

        // Section options
        add_settings_section(
                'slwsu_page_metrics_section_plugin_options', __('Deactivation', 'pmtx'), array($this, 'section_options'), 'slwsu_page_metrics_options'
        );
        // ...
        add_settings_field(
                'slwsu_page_metrics_delete_options', __('Delete options', 'pmtx'), array($this, 'delete_options'), 'slwsu_page_metrics_options', 'slwsu_page_metrics_section_plugin_options'
        );
        register_setting(
                'slwsu_page_metrics_settings', 'slwsu_page_metrics_delete_options'
        );

        /**
         * Support GRP
         */
        if ('true' === get_option('slwsu_is_active_grouper', 'false')):
            // Section grouper
            add_settings_section(
                    'slwsu_page_metrics_section_plugin_grouper', __('Group', 'pmtx'), array($this, 'section_grouper'), 'slwsu_page_metrics_options'
            );
            // ...
            add_settings_field(
                    'slwsu_page_metrics_grouper', __('Plugin Group', 'pmtx'), array($this, 'grouper_nom'), 'slwsu_page_metrics_options', 'slwsu_page_metrics_section_plugin_grouper'
            );
            register_setting(
                    'slwsu_page_metrics_settings', 'slwsu_page_metrics_grouper'
            );
        else:
            // Section NO grouper
            add_settings_section(
                    'slwsu_page_metrics_section_plugin_grouper', null, array($this, 'section_grouper_no'), 'slwsu_page_metrics_options'
            );
        endif;
    }

    /**
     * Plugin
     */
    public function section_plugin() {
        echo __('This section concerns the configuration of the plugin', 'pmtx') . '&nbsp;<strong><i>Page Métrics</i></strong>';
    }

    function display_metrics_activation() {
        ?>
        <?php echo __('Activate', 'pmtx'); ?> <input name="slwsu_page_metrics_activation" type="radio" value="true" <?php checked('true', get_option('slwsu_page_metrics_activation')); ?> />
        &nbsp;&nbsp;&nbsp;
        <?php echo __('Deactivate', 'pmtx'); ?> <input name="slwsu_page_metrics_activation" type="radio" value="false" <?php checked('false', get_option('slwsu_page_metrics_activation')); ?> />
        <p class="description"><?php echo __('Displays the bar Page Metrics (admin only).', 'pmtx'); ?> </p>
        <?php
    }

    function display_metrics_fps() {
        ?>
        <?php echo __('Activate', 'pmtx'); ?> <input name="slwsu_page_metrics_fps" type="radio" value="true" <?php checked('true', get_option('slwsu_page_metrics_fps')); ?> />
        &nbsp;&nbsp;&nbsp;
        <?php echo __('Deactivate', 'pmtx'); ?> <input name="slwsu_page_metrics_fps" type="radio" value="false" <?php checked('false', get_option('slwsu_page_metrics_fps')); ?> />
        <p class="description"><?php echo __('Enables the display of graphic FPS flow.', 'pmtx'); ?> </p>
        <?php
    }

    public function display_metrics_warnThreshold() {
        ?>
        <input id="slwsu_page_metrics_warnThreshold" name="slwsu_page_metrics_warnThreshold" value="<?php echo get_option('slwsu_page_metrics_warnThreshold'); ?>" type="text" class="regular-text" />
        <p class="description"><?php echo __('Warning (0.9 = 90%), over 90% of the budget, the result is displayed in yellow.', 'pmtx'); ?></p>
        <?php
    }

    public function display_metrics_ttfb() {
        ?>
        <input id="slwsu_page_metrics_ttfb" name="slwsu_page_metrics_ttfb" value="<?php echo get_option('slwsu_page_metrics_ttfb'); ?>" type="text" class="regular-text" />
        <p class="description"><?php echo __('Measures the time until the first byte of the base page is received by the browser (after redirects).', 'pmtx'); ?></p>
        <?php
    }

    public function display_metrics_domInteractive() {
        ?>
        <input id="slwsu_page_metrics_domInteractive" name="slwsu_page_metrics_domInteractive" value="<?php echo get_option('slwsu_page_metrics_domInteractive'); ?>" type="text" class="regular-text" />
        <p class="description"><?php echo __('Shows when the browser has finished analyzing all the HTML and DOM where the construction is complete.', 'pmtx'); ?></p>
        <?php
    }

    public function display_metrics_domComplete() {
        ?>
        <input id="slwsu_page_metrics_domComplete" name="slwsu_page_metrics_domComplete" value="<?php echo get_option('slwsu_page_metrics_domComplete'); ?>" type="text" class="regular-text" />
        <p class='description'><?php echo __('The entire treatment is completed, download all the resources on the page (images, css, js ...) is completed.', 'pmtx'); ?></p>
        <?php
    }

    public function display_metrics_firstPaint() {
        ?>
        <input id="slwsu_page_metrics_firstPaint" name="slwsu_page_metrics_firstPaint" value="<?php echo get_option('slwsu_page_metrics_firstPaint'); ?>" type="text" class="regular-text" />
        <p class="description"><?php echo __('Indicates the beginning of the display on the browser, off white content.', 'pmtx'); ?></p>
        <?php
    }

    public function display_metrics_pageLoad() {
        ?>
        <input id="slwsu_page_metrics_pageLoad" name="slwsu_page_metrics_pageLoad" value="<?php echo get_option('slwsu_page_metrics_pageLoad'); ?>" type="text" class="regular-text" />
        <p class="description"><?php echo __('Last step of loading a page. The browser launches an onload event that can trigger a logical additional application.', 'pmtx'); ?></p>
        <?php
    }

    public function display_metrics_requests() {
        ?>
        <input id="slwsu_page_metrics_requests" name="slwsu_page_metrics_requests" value="<?php echo get_option('slwsu_page_metrics_requests'); ?>" type="text" class="regular-text" />
        <p class="description"><?php echo __('Shows the number of HTTP requests.', 'pmtx'); ?></p>
        <?php
    }

    /**
     * Options
     */
    public function section_options() {
        echo __('This section is about saving plugin options of', 'pmtx') . '&nbsp;<strong><i>Page Métrics</i></strong>';
    }

    public function delete_options() {
        $input = get_option('slwsu_page_metrics_delete_options');
        ?>
        <input name="slwsu_page_metrics_delete_options" type="radio" value="true" <?php if ('true' == $input) echo 'checked="checked"'; ?> />
        <span class="description">On</span>
        &nbsp;
        <input name="slwsu_page_metrics_delete_options" type="radio" value="false" <?php if ('false' == $input) echo 'checked="checked"'; ?> />
        <span class="description">Off</span>
        &nbsp;-&nbsp;
        <span class="description"><?php echo __('Delete plugin options when disabling.', 'pmtx'); ?> </span>
        <?php
    }

    /**
     * Support GRP
     */
    public function section_grouper() {
        echo __('This section concerns the Grouper plugin group of', 'pmtx') . '&nbsp;<strong><i>Page Métrics</i></strong>';
    }

    public function grouper_nom() {
        $input = get_option('slwsu_page_metrics_grouper', 'Grouper');
        echo '<input id="slwsu_page_metrics_grouper" name="slwsu_page_metrics_grouper" value="' . $input . '" type="text" class="regular-text" />';
        echo '<p class="description">' . __('Specify here the Grouper group to attach', 'pmtx') . '&nbsp;<strong><i>Page Métrics</i></strong>.</p>';
        echo '<p>' . __('WARNING :: changing the value of this field amounts to modifying the name of the parent link in the WordPress admin menu !', 'pmtx') . '</p>';
        echo '<p>' . __('You can use this option to isolate this plugin or to add this plugin to an existing Grouper group.', 'pmtx') . '</p>';
    }

    public function section_grouper_no() {
        echo '<strong><i>Page Métrics</i></strong> ' . __('is compatible with Grouper', 'pmtx');
        if (file_exists(WP_PLUGIN_DIR . '/grouper')):
            echo '.<br />Grouper ' . __('is installed but does not appear to be enabled', 'pmtx') . ' : ';
            echo '<a href="plugins.php">' . __('you can activate', 'pmtx') . ' Grouper</a>';
        else:
            echo ' : <a href="https://web-startup.fr/grouper/" target="_blank">' . __('more information here', 'pmtx') . '</a>.';
        endif;
    }

    /**
     *
     */
    public function admin_css() {
        echo '<style>
            .page-metrics-modal-link {
                position: relative;
                float: right;
            }

            .page-metrics-modal {
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                opacity: 0;
                z-index: 99999;
                position: fixed;
                pointer-events: none;
                background: rgba(0,0,0,0.8);
                font-family: Arial, Helvetica, sans-serif;
                -webkit-transition: opacity 250ms ease-in;
                -moz-transition: opacity 250ms ease-in;
                transition: opacity 250ms ease-in;
            }

            .page-metrics-modal:target {
                opacity: 1;
                pointer-events: auto;
            }

            .page-metrics-modal > div {
                width: 400px;
                background: #fff;
                margin: 7% auto;
                position: relative;
                border-radius: 10px;
                padding: 5px 20px 13px 20px;
                background: -o-linear-gradient(bottom, rgb(245,245,245) 25%, rgb(232,232,232) 63%);
                background: -moz-linear-gradient(bottom, rgb(245,245,245) 25%, rgb(232,232,232) 63%);
                background: -webkit-linear-gradient(bottom, rgb(245,245,245) 25%, rgb(232,232,232) 63%);
            }

            .page-metrics-modal-close {
                top: 10px;
                right: 10px;
                font-weight: bold;
                position: absolute;
                text-align: center;
                text-decoration: none;
            }

            .page-metrics-modal-close:hover { color: #333; }

            #page-metrics-contact input[type="text"],
            #page-metrics-contact input[type="email"],
            #page-metrics-contact input[type="url"],
            #page-metrics-contact textarea,
            #page-metrics-contact button[type="submit"] {
                font:400 12px/16px "Open Sans", Helvetica, Arial, sans-serif;
            }

            fieldset {
                border: medium none !important;
                margin: 0 0 6px;
                min-width: 100%;
                padding: 0;
                width: 100%;
            }

            #page-metrics-contact input[type="text"],
            #page-metrics-contact input[type="email"],
            #page-metrics-contact input[type="tel"],
            #page-metrics-contact input[type="url"],
            #page-metrics-contact textarea {
                width:100%;
                border:1px solid #CCC;
                background:#FFF;
                margin:0 0 5px;
                padding:10px;
            }

            #page-metrics-contact input[type="text"]:hover,
            #page-metrics-contact input[type="email"]:hover,
            #page-metrics-contact input[type="tel"]:hover,
            #page-metrics-contact input[type="url"]:hover,
            #page-metrics-contact textarea:hover {
                -webkit-transition:border-color 0.3s ease-in-out;
                -moz-transition:border-color 0.3s ease-in-out;
                transition:border-color 0.3s ease-in-out;
                border:1px solid #AAA;
            }

            #page-metrics-contact textarea {
                height:100px;
                max-width:100%;
                resize:none;
                margin-bottom: 0px;
            }

            #page-metrics-contact input:focus,
            #page-metrics-contact textarea:focus {
                outline:0;
                border:1px solid #999;
            }

            ::-webkit-input-placeholder { color:#888; }
            :-moz-placeholder { color:#888; }
            ::-moz-placeholder { color:#888; }
            :-ms-input-placeholder { color:#888; }


            .page-metrics-contact-valide, .page-metrics-contact-error{
                padding: 8px;
                background-color: white;
            }
            .page-metrics-contact-valide{
                border-left: 4px solid #46b450;
            }
            .page-metrics-contact-error{
                border-left: 4px solid #dc3232;
            }
        </style>';
    }

}
