<?php
/**
 * Iran Speed Pro 2 - Core Loader Class
 * * این فایل مسئولیت بارگذاری تمامی پیش‌نیازها و راه‌اندازی هوک‌های اصلی افزونه را بر عهده دارد.
 * * @package    Iran_Speed_Pro
 * @subpackage Iran_Speed_Pro/core
 * @author     Hosein Qadimi
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // جلوگیری از دسترسی مستقیم
}

if ( ! class_exists( 'Iran_Speed_Pro' ) ) {

    class Iran_Speed_Pro {

        /**
         * نام منحصر به فرد افزونه برای تعریف هوک‌ها و اسلاگ‌ها
         */
        protected $plugin_name;

        /**
         * نسخه فعلی افزونه
         */
        protected $version;

        /**
         * سازنده کلاس - نقطه شروع اجرای منطق افزونه
         */
        public function __construct() {
            $this->plugin_name = 'iran-speed-pro';
            $this->version     = '2.0.0';

            $this->load_dependencies();
            $this->set_locale();
            $this->define_admin_hooks();
        }

        /**
         * بارگذاری تمامی ۱۵ فایل موجود در پوشه includes
         * ترتیب بارگذاری بر اساس اولویت وابستگی فایل‌ها تنظیم شده است.
         */
        private function load_dependencies() {
            $inc_path = plugin_dir_path( dirname( __FILE__ ) ) . 'includes/';

            // ۱. بارگذاری فایل‌های زیرساختی و ابزارها
            require_once $inc_path . 'class-helper.php';
            require_once $inc_path . 'class-settings-handler.php';
            require_once $inc_path . 'class-logger.php';

            // ۲. بارگذاری ماژول‌های بهینه‌سازی دیتابیس و فایل
            require_once $inc_path . 'class-db-optimizer.php';
            require_once $inc_path . 'class-orphan-detector.php';
            require_once $inc_path . 'class-cache-handler.php';
            
            // ۳. بارگذاری ماژول‌های مدیریت رسانه
            require_once $inc_path . 'class-webp-converter.php';
            require_once $inc_path . 'class-image-cleaner.php';
            
            // ۴. بارگذاری ماژول‌های امنیتی و نظارتی
            require_once $inc_path . 'class-network-shield.php';
            require_once $inc_path . 'class-integrity-checker.php';
            require_once $inc_path . 'class-resource-monitor.php';
            
            // ۵. بارگذاری ماژول‌های کنترل فرانت‌ند و اسکریپت‌ها
            require_once $inc_path . 'class-script-manager.php';
            require_once $inc_path . 'class-heartbeat-control.php';
            
            // ۶. بارگذاری رابط‌های دیتای JSON (در صورت وجود)
            // require_once $inc_path . 'class-json-handler.php'; 
        }

        /**
         * تنظیمات محلی‌سازی و فایل‌های ترجمه (i18n)
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
         * ثبت و راه‌اندازی کلاس مدیریت (Admin Area)
         * این بخش فقط در صورتی که کاربر در پیشخوان باشد لود می‌شود تا پرفورمنس سایت حفظ شود.
         */
        private function define_admin_hooks() {
            if ( is_admin() ) {
                require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-iran-speed-pro-admin.php';

                // ایجاد نمونه از کلاس مدیریت و پاس دادن متغیرهای اصلی
                $admin = new ISP_Admin( $this->plugin_name, $this->version );

                // ثبت منوی اصلی در پنل وردپرس
                add_action( 'admin_menu', array( $admin, 'add_menu' ) );

                // ثبت فایل‌های CSS و JS در صفحات مدیریت
                add_action( 'admin_enqueue_scripts', array( $admin, 'enqueue_assets' ) );
            }
        }

        /**
         * متد اجرای نهایی
         * این متد در فایل ریشه (Root) صدا زده می‌شود تا فرآیند شروع به کار افزونه را تکمیل کند.
         */
        public function run() {
            // ثبت گزارش در لاگ افزونه جهت اطمینان از صحت بارگذاری کامل
            if ( class_exists( 'ISP_Logger' ) ) {
                ISP_Logger::log( "Core Engine Started Successfully. Version: " . $this->version );
            }
        }

        /**
         * دریافت نام افزونه (Getter)
         */
        public function get_plugin_name() {
            return $this->plugin_name;
        }

        /**
         * دریافت نسخه افزونه (Getter)
         */
        public function get_version() {
            return $this->version;
        }
    }
}