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
         * پاکسازی تمام حافظه‌های کش در دسترس
         */
        public function clear_all_cache() {
            $this->clear_wp_object_cache();
            $this->clear_opcache();
            $this->clear_third_party_plugins();
            return true;
        }

        /**
         * پاکسازی Object Cache وردپرس
         */
        private function clear_wp_object_cache() {
            wp_cache_flush();
        }

        /**
         * پاکسازی PHP OPCache (در صورت فعال بودن روی سرور)
         */
        private function clear_opcache() {
            if (function_exists('opcache_reset')) {
                @opcache_reset();
            }
        }

        /**
         * هماهنگی با افزونه‌های کش معروف (WP Rocket, LiteSpeed, Fast-Cache)
         */
        private function clear_third_party_plugins() {
            // LiteSpeed Cache
            if (has_action('litespeed_purge_all')) {
                do_action('litespeed_purge_all');
            }
            // WP Rocket
            if (function_exists('rocket_clean_domain')) {
                rocket_clean_domain();
            }
            // Autoptimize
            if (class_exists('autoptimizeCache')) {
                autoptimizeCache::clearall();
            }
        }
    }
}