<?php
/**
 * Iran Speed Pro 2 - Core Engine
 * این فایل قلب افزونه است و تمام کلاس‌های Includes را لود می‌کند.
 * مسیر: core/class-iran-speed-pro.php
 */

if (!defined('ABSPATH')) {
    exit;
}

if (!class_exists('Iran_Speed_Pro')) {

    class Iran_Speed_Pro {

        protected $plugin_name;
        protected $version;

        public function __construct() {
            $this->plugin_name = 'iran-speed-pro';
            $this->version = '2.0.0';

            $this->load_dependencies();
            $this->set_locale();
            $this->define_admin_hooks();
        }

        /**
         * بارگذاری تمام ۱۵ فایل پوشه includes به صورت دستی و دقیق
         */
        private function load_dependencies() {
            $inc_path = plugin_dir_path(dirname(__FILE__)) . 'includes/';

            // ۱. توابع پایه و تنظیمات
            require_once $inc_path . 'class-helper.php';
            require_once $inc_path . 'class-settings-handler.php';
            require_once $inc_path . 'class-logger.php';

            // ۲. کلاس‌های عملیاتی دیتابیس و کش
            require_once $inc_path . 'class-db-optimizer.php';
            require_once $inc_path . 'class-orphan-detector.php';
            require_once $inc_path . 'class-cache-handler.php';
            require_once $inc_path . 'class-heartbeat-control.php';
            require_once $inc_path . 'class-script-manager.php';

            // ۳. کلاس‌های رسانه و امنیت
            require_once $inc_path . 'class-webp-converter.php';
            require_once $inc_path . 'class-image-cleaner.php';
            require_once $inc_path . 'class-network-shield.php';
            require_once $inc_path . 'class-integrity-checker.php';
            require_once $inc_path . 'class-resource-monitor.php';
        }

        /**
         * تنظیمات زبان و ترجمه
         */
        private function set_locale() {
            add_action('plugins_loaded', function() {
                load_plugin_textdomain('iran-speed-pro', false, dirname(dirname(plugin_basename(__FILE__))) . '/languages/');
            });
        }

        /**
         * تعریف هوک‌های بخش مدیریت (اتصال به پوشه admin)
         */
        private function define_admin_hooks() {
            // فراخوانی کلاس مدیریت
            require_once plugin_dir_path(dirname(__FILE__)) . 'admin/class-iran-speed-pro-admin.php';
            
            // ایجاد نمونه از کلاس ادمین و پاس دادن نام و نسخه افزونه
            $admin = new ISP_Admin($this->plugin_name, $this->version);
            
            // ثبت منو و استایل‌ها
            add_action('admin_menu', array($admin, 'add_menu'));
            add_action('admin_enqueue_scripts', array($admin, 'enqueue_assets'));
        }

        /**
         * متد اصلی اجرا (صدا زده شده در فایل ریشه)
         */
        public function run() {
            // ثبت گزارش در لاگ افزونه
            if (class_exists('ISP_Logger')) {
                ISP_Logger::log("افزونه ایران اسپید پرو با موفقیت لود و آماده کار شد.");
            }
        }
    }
}