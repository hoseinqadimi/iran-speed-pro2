<?php
/**
 * Iran Speed Pro 2 - Core Loader Class
 * این فایل مسئول مدیریت چرخه حیات افزونه و بارگذاری تمامی زیرمجموعه‌هاست.
 * * @package    Iran_Speed_Pro
 * @subpackage Iran_Speed_Pro/core
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // امنیت: جلوگیری از دسترسی مستقیم به فایل
}

if ( ! class_exists( 'Iran_Speed_Pro' ) ) {

    class Iran_Speed_Pro {

        /**
         * نام منحصر به فرد افزونه
         */
        protected $plugin_name;

        /**
         * نسخه فعلی افزونه
         */
        protected $version;

        /**
         * سازنده کلاس - نقطه آغازین تمام فرآیندها
         */
        public function __construct() {
            $this->plugin_name = 'iran-speed-pro';
            $this->version     = '2.0.0';

            $this->load_dependencies();
            $this->set_locale();
            
            // فقط در صورت حضور در پنل مدیریت، کلاس مدیریت را لود کن (بهینه سازی مصرف رم)
            if ( is_admin() ) {
                $this->define_admin_hooks();
            }
        }

        /**
         * بارگذاری تمامی ۱۵ فایل موجود در پوشه includes
         * نکته تخصصی: ترتیب بارگذاری بر اساس وابستگی کلاس‌ها (Dependency) رعایت شده است.
         */
        private function load_dependencies() {
            $inc_path = plugin_dir_path( dirname( __FILE__ ) ) . 'includes/';

            // ۱. لایه پایه (Infrastructure) - بقیه کلاس‌ها به این ۳ فایل نیاز دارند
            require_once $inc_path . 'class-helper.php';
            require_once $inc_path . 'class-settings-handler.php';
            require_once $inc_path . 'class-logger.php';

            // ۲. لایه عملیاتی (Operations)
            require_once $inc_path . 'class-db-optimizer.php';
            require_once $inc_path . 'class-cache-handler.php';
            require_once $inc_path . 'class-orphan-detector.php';
            
            // ۳. لایه رسانه و فایل (Media & Files)
            require_once $inc_path . 'class-webp-converter.php';
            require_once $inc_path . 'class-image-cleaner.php';
            
            // ۴. لایه نظارت و امنیت (Monitoring & Security)
            require_once $inc_path . 'class-resource-monitor.php';
            require_once $inc_path . 'class-integrity-checker.php';
            require_once $inc_path . 'class-network-shield.php';
            
            // ۵. لایه کنترل اسکریپت و بهینه‌سازی فرانت (Frontend Opt)
            require_once $inc_path . 'class-script-manager.php';
            require_once $inc_path . 'class-heartbeat-control.php';

            // ۶. لایه داده‌های JSON و مکمل
            // در صورتی که فایل class-json-handler.php در سیستم شما موجود است، خط زیر را فعال کنید:
            // require_once $inc_path . 'class-json-handler.php';
        }

        /**
         * تنظیمات مربوط به ترجمه و بومی‌سازی افزونه
         */
        private function set_locale() {
            add_action( 'plugins_loaded', function() {
                load_plugin_textdomain(
                    $this->plugin_name,
                    false,
                    dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
                );
            });
        }

        /**
         * تعریف و ثبت هوک‌های مربوط به پنل مدیریت (Admin)
         */
        private function define_admin_hooks() {
            require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-iran-speed-pro-admin.php';

            // ایجاد نمونه از کلاس مدیریت و تزریق متغیرهای اصلی به آن
            $admin = new ISP_Admin( $this->plugin_name, $this->version );

            // اتصال متدهای کلاس مدیریت به اکشن‌های وردپرس
            add_action( 'admin_menu', array( $admin, 'add_menu' ) );
            add_action( 'admin_enqueue_scripts', array( $admin, 'enqueue_assets' ) );
        }

        /**
         * اجرای نهایی افزونه
         * این متد از فایل اصلی (Root) صدا زده می‌شود.
         */
        public function run() {
            // ثبت لاگ سیستمی برای تایید لود کامل تمام ماژول‌ها
            if ( class_exists( 'ISP_Logger' ) ) {
                ISP_Logger::log( "Iran Speed Pro Core [v{$this->version}] fully initialized." );
            }
        }

        /**
         * متد دریافت نسخه افزونه (Getter)
         */
        public function get_version() {
            return $this->version;
        }
    }
}