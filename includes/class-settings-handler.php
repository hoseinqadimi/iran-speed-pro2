<?php
/**
 * کلاس مدیریت تنظیمات ایران اسپید پرو
 * مسیر: includes/class-settings-handler.php
 */

if (!defined('ABSPATH')) {
    exit;
}

if (!class_exists('ISP_Settings_Handler')) {

    class ISP_Settings_Handler {

        private $option_name = 'iran_speed_pro_settings';

        /**
         * دریافت تمام تنظیمات به صورت یکجا با مقادیر پیش‌فرض
         */
        public function get_all_settings() {
            $defaults = array(
                'cache_enabled'      => 1,
                'webp_enabled'       => 0,
                'heartbeat_mode'     => 'modify',
                'heartbeat_interval' => 60,
                'security_shield'    => 1,
                'log_enabled'        => 1,
                'last_cleanup'       => ''
            );

            $settings = get_option($this->option_name, $defaults);
            return wp_parse_args($settings, $defaults);
        }

        /**
         * ذخیره یک تنظیم خاص
         */
        public function set_setting($key, $value) {
            $settings = $this->get_all_settings();
            $settings[$key] = $value;
            return update_option($this->option_name, $settings);
        }

        /**
         * خواندن یک تنظیم خاص
         */
        public function get_setting($key) {
            $settings = $this->get_all_settings();
            return isset($settings[$key]) ? $settings[$key] : null;
        }

        /**
         * پاکسازی کل تنظیمات (هنگام حذف افزونه)
         */
        public function delete_all_settings() {
            return delete_option($this->option_name);
        }
    }
}