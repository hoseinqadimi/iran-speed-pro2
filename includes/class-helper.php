<?php
/**
 * کلاس توابع کمکی و ابزارهای عمومی
 * مسیر: includes/class-helper.php
 */

if (!defined('ABSPATH')) {
    exit;
}

if (!class_exists('ISP_Helper')) {

    class ISP_Helper {

        /**
         * تبدیل بایت به واحدهای خواناتر (KB, MB, GB)
         */
        public static function format_bytes($bytes, $precision = 2) {
            $units = array('B', 'KB', 'MB', 'GB', 'TB');
            $bytes = max($bytes, 0);
            $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
            $pow = min($pow, count($units) - 1);
            $bytes /= pow(1024, $pow);

            return round($bytes, $precision) . ' ' . $units[$pow];
        }

        /**
         * متد ایمن‌سازی ورودی‌ها (Sanitization)
         */
        public static function clean($data) {
            if (is_array($data)) {
                return array_map(array('self', 'clean'), $data);
            }
            return sanitize_text_field($data);
        }

        /**
         * بررسی اینکه آیا کاربر فعلی اجازه دسترسی به تنظیمات را دارد
         */
        public static function is_admin() {
            return current_user_can('manage_options');
        }

        /**
         * دریافت زمان فعلی به فرمت وردپرس
         */
        public static function get_current_time() {
            return current_time('mysql');
        }
    }
}