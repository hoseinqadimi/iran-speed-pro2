<?php
/**
 * کلاس مدیریت و پاکسازی کش
 * مسیر: includes/class-cache-handler.php
 */

if (!defined('ABSPATH')) {
    exit;
}

if (!class_exists('ISP_Cache_Handler')) {

    class ISP_Cache_Handler {

        /**
         * پاکسازی سراسری کش
         */
        public function clear_all_cache() {
            // ۱. پاکسازی کش داخلی وردپرس
            wp_cache_flush();

            // ۲. پاکسازی PHP OPCache
            if (function_exists('opcache_reset')) {
                @opcache_reset();
            }

            // ۳. پاکسازی افزونه‌های جانبی
            $this->clear_third_party();

            return true;
        }

        /**
         * تعامل با لایت‌اسپید، راکت و غیره
         */
        private function clear_third_party() {
            // LiteSpeed
            if (has_action('litespeed_purge_all')) {
                do_action('litespeed_purge_all');
            }
            // WP Rocket
            if (function_exists('rocket_clean_domain')) {
                rocket_clean_domain();
            }
            // WP Fast Cache
            if (isset($GLOBALS['wp_fastest_cache']) && method_exists($GLOBALS['wp_fastest_cache'], 'deleteCache')) {
                $GLOBALS['wp_fastest_cache']->deleteCache();
            }
        }
    }
}