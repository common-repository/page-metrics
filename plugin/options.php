<?php

/**
 * @package Page Métrics
 * @version 1.5.1
 */

defined('ABSPATH') or exit();

class slwsu_page_metrics_options {
    
    /**
     * ...
     */
    public static function options() {
        $return = [
            // Options plugin
            'activation' => 'false',
            'fps' => 'true',
            'warnThreshold' => 0.90,
            'ttfb' => 250,
            'domInteractive' => 300,
            'domComplete' => 300,
            'firstPaint' => 500,
            'pageLoad' => 1000,
            'requests' => 30,
            // Options config
            'delete_options' => 'false',
            'grouper' => 'Grouper'
        ];
        return $return;
    }
    
    /**
     * ...
     */
    public static function get_options() {
        $return = [];
        foreach (self::options() as $k => $v):
            $return['slwsu_page_metrics_' . $k] = get_option('slwsu_page_metrics_' . $k, $v);
        endforeach;
        unset($k, $v);

        return $return;
    }
    
    /**
     * ...
     */
    public static function get_transient() {
        $return = get_transient('slwsu_page_metrics_options');
        return $return;
    }
    
    /**
     * ...
     */
    public static function set_transient($aOptions) {
        set_transient('slwsu_page_metrics_options', $aOptions, '');
    }

}
