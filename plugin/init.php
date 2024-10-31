<?php

/**
 * @package Page Métrics
 * @version 1.5.1
 */

defined('ABSPATH') or exit();

class slwsu_page_metrics_plugin_init{
    
    public function __construct(){
        $this->_init();
    }
    
    private function _init(){
        if(is_admin()):
            include_once plugin_dir_path(__FILE__) . 'admin/init.php';
            new slwsu_page_metrics_admin_init();
        else:
            include_once plugin_dir_path(__FILE__) . 'front/init.php';
            new slwsu_page_metrics_front_init();
        endif;
    }
    
}