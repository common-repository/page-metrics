<?php

/*
  Plugin Name: Page Métrics
  Plugin URI: http://web-startup.fr/
  Description: Page Métrics un plugin slwsu - modèle de base.
  Version: 1.5.1
  Author: Steeve Lefebvre
  Author URI: web-startup.fr/
  License: GPLv2 or later
  License URI: http://www.gnu.org/licenses/gpl-2.0.html
  Text Domain: pmtx
  Contributors: webstartup, Benoti, citoyensdebout
  ------------------------------------------------------------------------------
  Note pour les anglophones : quand un code commenté en anglais me plait
  et qu'aucune traduction n'est disponible, je dois me démerder.
  Merci de bien vouloir me rendre la pareille :-þ
 */

defined('ABSPATH') or exit();

/**
 * GRP support
 */
if (!is_admin()):
    $GROUPER_PAGE_METRICS = false;
else:
    if ('true' === get_option('slwsu_is_active_grouper', 'false')):
        if (!class_exists('slwsu_grouper_init')):
            require_once WP_PLUGIN_DIR . '/grouper/init.php';
        endif;
        $GROUPER_PAGE_METRICS = new slwsu_grouper_init(get_option('slwsu_page_metrics_grouper'), '4.4', '5.5');
    endif;
endif;

/**
 * Entrée du plugin
 */
class slwsu_page_metrics {

    public function __construct() {

        // Hook
        register_activation_hook(__FILE__, array($this, 'activate'));
        register_deactivation_hook(__FILE__, array($this, 'deactivate'));
        add_action('plugins_loaded', array($this, 'text_domain'));
        add_filter('plugin_action_links_' . plugin_basename(__FILE__), array($this, 'setting_links'));

        // Grouper 
        global $GROUPER_PAGE_METRICS;
        if (is_object($GROUPER_PAGE_METRICS)):
            if (false === $GROUPER_PAGE_METRICS->wp_status or false === $GROUPER_PAGE_METRICS->php_status):
                add_action('admin_init', array($this, 'deactivate_auto'));
            endif;
        endif;

        // Plugin
        $this->plugin();
    }

    /**
     * Plugin
     */
    private function plugin() {
        if (false !== get_option('bfsl_page-metrics_activation')):
            $this->bfsl_maj();
        endif;

        if (false !== get_option('grp_activation_page_metrics')):
            $this->grp_maj();
        endif;

        if (false !== get_option('slwsu_activation_page_metrics')):
            $this->slwsu_maj();
        endif;

        include_once plugin_dir_path(__FILE__) . 'plugin/init.php';
        new slwsu_page_metrics_plugin_init();
    }

    /**
     * 
     */
    public function bfsl_maj() {
        include_once plugin_dir_path(__FILE__) . 'options.php';
        $options = slwsu_page_metrics_options::options();
        foreach ($options as $k => $v):
            $option = get_option('bfsl_page-metrics_' . $k);
            delete_option('bfsl_page-metrics_' . $k);
            add_option('slwsu_page_metrics_' . $k, $option);
        endforeach;
        delete_transient('bfsl_page-metrics_options');
    }

    /**
     * 
     */
    public function grp_maj() {
        include_once plugin_dir_path(__FILE__) . 'options.php';
        $options = slwsu_options_page_metrics::options();
        foreach ($options as $k => $v):
            $option = get_option('grp_page_metrics_' . $k);
            delete_option('grp_page_metrics_' . $k);
            add_option('slwsu_page_metrics_' . $k, $option);
        endforeach;
        delete_option('grp_page_metrics_groupe');
        delete_option('grp_page_metrics_delete_options');
        delete_transient('grp_page_metrics_options');
    }

    /**
     * 
     */
    public function slwsu_maj() {
        include_once plugin_dir_path(__FILE__) . 'options.php';
        $options = slwsu_options_page_metrics::options();
        foreach ($options as $k => $v):
            $option = get_option('slwsu_' . $k . '_page_metrics');
            delete_option('slwsu_' . $k . '_page_metrics');
            add_option('slwsu_page_metrics_' . $k, $option);
        endforeach;
        delete_transient('slwsu_options_page_metrics');
    }

    /**
     * Languages
     */
    public static function text_domain() {
        load_plugin_textdomain('pmtx', false, dirname(plugin_basename(__FILE__)) . '/languages');
    }

    /**
     * Liens
     */
    public function setting_links($aLinks) {
        $links[] = '<a href="https://web-startup.fr/page-metrics/">' . __('Page', 'pmtx') . '</a>';
        $links[] = '<a href="' . admin_url('admin.php?page=page-metrics') . '">' . __('Settings', 'pmtx') . '</a>';
        return array_merge($links, $aLinks);
    }

    /**
     * Activation
     */
    public static function activate() {
        $option = slwsu_page_metrics::options();
        foreach ($option as $k => $v):
            add_option($k, $v);
        endforeach;
        unset($k, $v);
    }

    /**
     * Désactivation
     */
    public static function deactivate() {
        if ('true' === get_option('slwsu_page_metrics_delete_options', 'false')):
            $option = slwsu_page_metrics::options();
            foreach ($option as $k => $v):
                delete_option($k);
            endforeach;
            unset($k, $v);
        endif;
    }

    /**
     * Options
     */
    public static function options() {
        include_once plugin_dir_path(__FILE__) . 'plugin/options.php';
        return slwsu_page_metrics_options::get_options();
    }

    /**
     * Désactivation automatique
     */
    public static function deactivate_auto() {
        // On désactive le plugin
        deactivate_plugins(plugin_basename(__FILE__));
    }

}

/**
 * 
 */
new slwsu_page_metrics();
