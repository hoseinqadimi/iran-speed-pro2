<?php
/**
 * هسته اصلی و هماهنگ‌کننده تمام بخش‌های افزونه
 * مسیر: core/class-iran-speed-pro.php
 */

if (!defined('ABSPATH')) {
    exit;
}

if (!class_exists('Iran_Speed_Pro')) {

    class Iran_Speed_Pro {

        protected $loader;
        protected $plugin_name;
        protected $version;

        public function __construct() {
            $this->plugin_name = 'iran-speed-pro';
            $this->version = '2.0.0';

            $this->load_dependencies();
            $this->set_locale();
            $this->define_admin_hooks();
            $this->define_public_hooks();
            $this->initialize_components();
        }

        /**
         * فراخوانی تمام ۱۵ فایل پوشه includes
         */
        private function load_dependencies() {
            $inc_path = plugin_dir_path(dirname(__FILE__)) . 'includes/';

            // ۱. توابع پایه و تنظیمات
            require_once $inc_path . 'class-helper.php';
            require_once $inc_path . 'class-settings-handler.php';
            require_once $inc_path . 'class-logger.php';

            // ۲. بهینه‌سازها
            require_once $inc_path . 'class-db-optimizer.php';
            require_once $inc_path . 'class-orphan-detector.php';
            require_once $inc_path . 'class-cache-handler.php';
            require_once $inc_path . 'class-heartbeat-control.php';
            require_once $inc_path . 'class-script-manager.php';

            // ۳. رسانه و تصاویر
            require_once $inc_path . 'class-webp-converter.php';
            require_once $inc_path . 'class-image-cleaner.php';

            // ۴. امنیت و مانیتورینگ
            require_once $inc_path . 'class-network-shield.php';
            require_once $inc_path . 'class-integrity-checker.php';
            require_once $inc_path . 'class-resource-monitor.php';
        }

        /**
         * مقداردهی اولیه کلاس‌های عملیاتی
         */
        private function initialize_components() {
            $settings = new ISP_Settings_Handler();
            $all_options = $settings->get_all_settings();

            // فعال‌سازی ضربان قلب بر اساس تنظیمات
            $heartbeat = new ISP_Heartbeat_Control();
            $heartbeat->apply_settings($all_options['heartbeat_mode'], $all_options['heartbeat_interval']);

            // فعال‌سازی زره امنیتی ایران
            if ($all_options['security_shield']) {
                new ISP_Network_Shield();
            }

            // لاگ کردن شروع به کار افزونه
            ISP_Logger::log("افزونه ایران اسپید پرو با موفقیت لود شد.");
        }

        /**
         * تنظیمات زبان (Languages)
         */
        private function set_locale() {
            add_action('plugins_loaded', function() {
                load_plugin_textdomain('iran-speed-pro', false, dirname(dirname(plugin_basename(__FILE__))) . '/languages/');
            });
        }

        /**
         * هوک‌های بخش مدیریت (Admin)
         */
        private function define_admin_hooks() {
            // در اینجا کلاس Admin_Menu فراخوانی خواهد شد
        }

        /**
         * هوک‌های بخش کاربری (Frontend)
         */
        private function define_public_hooks() {
            // بهینه‌سازی‌های CSS و JS در اینجا لود می‌شوند
        }

        public function run() {
            // استارت نهایی
        }
    }
}