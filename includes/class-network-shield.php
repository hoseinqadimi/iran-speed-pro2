<?php
/**
 * کلاس محافظت شبکه و امنیت (Iran Speed Pro)
 * وظیفه: مسدودسازی دسترسی‌های غیرمجاز و بهینه‌سازی امنیت وردپرس
 * مسیر: includes/class-network-shield.php
 */

if (!defined('ABSPATH')) {
    exit;
}

if (!class_exists('ISP_Network_Shield')) {

    class ISP_Network_Shield {

        /**
         * اجرای تنظیمات حفاظتی
         */
        public function init() {
            add_action('init', array($this, 'hide_wordpress_version'));
            add_filter('xmlrpc_enabled', '__return_false'); // غیرفعال کردن XML-RPC برای جلوگیری از حملات
        }

        /**
         * مخفی کردن ورژن وردپرس برای جلوگیری از شناسایی حفره‌های امنیتی توسط هکرها
         */
        public function hide_wordpress_version() {
            remove_action('wp_head', 'wp_generator');
        }

        /**
         * جلوگیری از دسترسی به فایل‌های حساس از طریق HTTP
         */
        public function protect_sensitive_files() {
            // این متد می‌تواند کدهای لازم برای فایل .htaccess را تولید کند
            $rules = "
<Files .htaccess>
order allow,deny
deny from all
</Files>
<Files wp-config.php>
order allow,deny
deny from all
</Files>";
            return $rules;
        }

        /**
         * مسدودسازی اسکن نام کاربری (Author Scan) برای جلوگیری از Brute Force
         */
        public function block_author_scanning() {
            if (is_admin()) return;
            
            if (preg_match('/author=([0-9]*)/i', $_SERVER['QUERY_STRING'])) {
                wp_die('Author scanning is disabled for security reasons.');
            }
        }
    }
}