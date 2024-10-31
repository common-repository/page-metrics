<?php

/**
 * @package Page Métrics
 * @since 1.5.1
 */
defined('ABSPATH') or exit();

class slwsu_page_metrics_admin_init {

    /**
     * 
     */
    public function __construct() {
        $this->admin_page();
    }

    /**
     * 
     */
    public function admin_page() {
        // Page simple
        include_once plugin_dir_path(__FILE__) . 'page.php';
        new slwsu_page_metrics_admin_page();
    }

}
