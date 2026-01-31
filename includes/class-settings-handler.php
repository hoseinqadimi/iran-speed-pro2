<?php
/**
 * کلاس مدیریت تنظیمات افزونه
 * مسیر: includes/class-settings-handler.php
 */

if (!defined('ABSPATH')) {
    exit;
}

if (!class_exists('ISP_Settings_Handler')) {

    class ISP_Settings_Handler {

        private $option_name = 'iran_speed_pro_settings';

        /**
         * دریافت تمام تنظیمات با مقادیر پیش‌فرض
         */
        public function get_all_settings() {
            $defaults = array(
                'webp_enabled'       => 0,
                'heartbeat_mode'     => 'modify',
                'heartbeat_interval' => 60,
                'security_shield'    => 1,
                'cleanup_interval'   => 'weekly',
                'log_enabled'        => 1
            );

            $settings = get_option($this->option_name, $defaults);
            return wp_parse_args($settings, $defaults);
        }

        /**
         * بروزرسانی یک تنظیم خاص
         */
        public function update_setting($key, $value) {
            $settings = $this->get_all_settings();
            $settings[$key] = $value;
            return update_option($this->option_name, $settings);
        }

        /**
         * دریافت مقدار یک کلید خاص
         */
        public function get_setting($key, $default = false) {
            $settings = $this->get_all_settings();
            return isset($settings[$key]) ? $settings[$key] : $default;
        }
    }
}