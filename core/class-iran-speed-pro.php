<?php
/**
 * Iran Speed Pro 2 - Core Engine
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
         * بارگذاری تمام ۱۵ فایل پوشه includes با مسیردهی دقیق
         */
        private function load_dependencies() {
            $inc_path = plugin_dir_path(dirname(__FILE__)) . 'includes/';

            // بارگذاری فایل‌های پایه و ابزارها
            require_once $inc_path . 'class-helper.php';
            require_once $inc_path . 'class-settings-handler.php';
            require_once $inc_path . 'class-logger.php';
            
            // بارگذاری ماژول‌های بهینه‌سازی
            require_once $inc_path . 'class-db-optimizer.php';
            require_once $inc_path . 'class-orphan-detector.php';
            require_once $inc_path . 'class-cache-handler.php';
            require_once $inc_path . 'class-heartbeat-control.php';
            require_once $inc_path . 'class-script-manager.php';
            
            // بارگذاری ماژول‌های رسانه و امنیت
            require_once $inc_path . 'class-webp-converter.php';
            require_once $inc_path . 'class-image-cleaner.php';
            require_once $inc_path . 'class-network-shield.php';
            require_once $inc_path . 'class-integrity-checker.php';
            require_once $inc_path . 'class-resource-monitor.php';
        }

        private function set_locale() {
            add_action('plugins_loaded', function() {
                load_plugin_textdomain('iran-speed-pro', false, dirname(dirname(plugin_basename(__FILE__))) . '/languages/');
            });
        }

        /**
         * اتصال به بخش مدیریت (Admin)
         */
        private function define_admin_hooks() {
            require_once plugin_dir_path(dirname(__FILE__)) . 'admin/class-iran-speed-pro-admin.php';
            
            $admin = new ISP_Admin($this->plugin_name, $this->version);
            
            add_action('admin_menu', array($admin, 'add_menu'));
            add_action('admin_enqueue_scripts', array($admin, 'enqueue_assets'));
        }

        /**
         * متد اجرای نهایی برای راه اندازی فرآیندهای پس‌زمینه
         */
        public function run() {
            // ثبت لاگ شروع به کار
            if (class_exists('ISP_Logger')) {
                ISP_Logger::log("افزونه ایران اسپید پرو با موفقیت لود و اجرا شد.");
            }
        }
    }
}