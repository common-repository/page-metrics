<?php

/**
 * @package Page Métrics
 * @since 1.5.1
 */

defined('WP_UNINSTALL_PLUGIN') or exit();

// Options
include_once plugin_dir_path(__FILE__) . 'plugin/options.php';
$aOptions = slwsu_page_metrics_options::get_options();
foreach ($aOptions as $k => $v):
    delete_option($k);
endforeach;
unset($k, $v);

// Transient
delete_transient('slwsu_page_metrics_options');