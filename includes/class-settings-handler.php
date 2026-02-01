<?php
/**
 * مدیریت تنظیمات افزونه
 * این کلاس زیربنای تمام ماژول‌ها برای خواندن و نوشتن تنظیمات است.
 * * @package Iran_Speed_Pro
 */

if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'ISP_Settings_Handler' ) ) {
    class ISP_Settings_Handler {

        /**
         * نام کلید اصلی تنظیمات در جدول options وردپرس
         */
        private $option_name = 'isp_plugin_settings';

        /**
         * تنظیمات پیش‌فرض افزونه
         */
        private $defaults = [
            'db_auto_optimize'    => 'off',
            'webp_conversion'     => 'on',
            'cache_expiration'    => 24,
            'network_shield_lv'   => 'medium',
            'heartbeat_frequency' => 'slow'
        ];

        /**
         * دریافت یک تنظیم خاص
         */
        public function get_setting( $key ) {
            $settings = get_option( $this->option_name, $this->defaults );
            return isset( $settings[$key] ) ? $settings[$key] : (isset($this->defaults[$key]) ? $this->defaults[$key] : null);
        }

        /**
         * دریافت تمام تنظیمات به صورت یکجا
         */
        public function get_all_settings() {
            return get_option( $this->option_name, $this->defaults );
        }

        /**
         * بروزرسانی یک یا چند تنظیم
         */
        public function update_settings( $new_data ) {
            $current_settings = $this->get_all_settings();
            $updated_settings = wp_parse_args( $new_data, $current_settings );
            
            return update_option( $this->option_name, $updated_settings );
        }

        /**
         * متد کمکی برای پاکسازی داده‌های ورودی قبل از ذخیره
         */
        public function sanitize_settings( $input ) {
            $sanitized = [];
            foreach ( $input as $key => $value ) {
                $sanitized[$key] = sanitize_text_field( $value );
            }
            return $sanitized;
        }
    }
}