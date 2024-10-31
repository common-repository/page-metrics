<?php

/**
 * @package Page Métrics
 * @version 1.5.1
 */

defined('ABSPATH') or exit();

class slwsu_page_metrics_front_init {

    public function __construct() {
        if ('true' === get_option('slwsu_page_metrics_activation')):
            add_action('init', array($this, '_init'), 999);
        endif;
    }

    /**
     * Init
     */
    public function _init() {

        $current_user = get_current_user_id();

        if ($current_user == 0) {
            return array();
        } else {
            $user = new WP_User($current_user);
            if (!empty($user->roles) && is_array($user->roles)) {
                foreach ($user->roles as $role):
                    if ($role === 'customer' or $role === 'user' or $role === 'subscriber') {
                        return array();
                    } else {
                        $this->add_js();
                    }
                endforeach;
            }
        }
    }

    /**
     * Add js
     */
    public function add_js() {
        add_action('wp_footer', array($this, 'js_to_footer'), 999);
        add_action('wp_enqueue_scripts', array($this, 'load_js'), 1000);
    }

    /**
     * Load js
     */
    public function load_js() {
        wp_register_script(
                'slWsu_justice_js', plugins_url('/js/justice.min.js', __FILE__), array('jquery')
        );

        wp_enqueue_script('slWsu_justice_js');
    }

    /**
     * Js to footer
     */
    public function js_to_footer() {
        ?>
        <script type="text/javascript">
            /* global Justice */
            Justice.init({
                metrics: {
                    TTFB: {budget: <?php echo get_option('slwsu_page_metrics_ttfb'); ?>},
                    domInteractive: {budget: <?php echo get_option('slwsu_page_metrics_domInteractive'); ?>},
                    domComplete: {budget: <?php echo get_option('slwsu_page_metrics_domComplete'); ?>},
                    firstPaint: {budget: <?php echo get_option('slwsu_page_metrics_firstPaint'); ?>},
                    pageLoad: {budget: <?php echo get_option('slwsu_page_metrics_pageLoad'); ?>},
                    requests: {budget: <?php echo get_option('slwsu_page_metrics_requests'); ?>}
                },
                showFPS: <?php echo get_option('slwsu_page_metrics_fps'); ?>,
                warnThreshold: <?php echo get_option('slwsu_page_metrics_warnThreshold'); ?>,
                chartType: 'spline'
            });
        </script>
        <?php
    }

}