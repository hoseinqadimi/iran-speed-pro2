<?php
/**
 * Iran Speed Pro 2 - Admin Area Controller
 * این فایل مسئول مدیریت تمامی عملیات‌های سمت پیشخوان، ثبت منوها و پردازش درخواست‌های AJAX با رعایت پروتکل‌های امنیتی است.
 *
 * @package    Iran_Speed_Pro
 * @subpackage Iran_Speed_Pro/admin
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // امنیت: جلوگیری از دسترسی مستقیم
}

if ( ! class_exists( 'ISP_Admin' ) ) {

    class ISP_Admin {

        /**
         * نام افزونه جهت استفاده در تعریف هوک‌ها
         */
        private $plugin_name;

        /**
         * نسخه افزونه جهت مدیریت کش فایل‌های CSS/JS
         */
        private $version;

        /**
         * سازنده کلاس مدیریت
         */
        public function __construct( $plugin_name, $version ) {
            $this->plugin_name = $plugin_name;
            $this->version     = $version;

            // ثبت هندلرهای AJAX برای پردازش درخواست‌های داشبورد
            $this->register_ajax_actions();
        }

        /**
         * ایجاد منوی اصلی افزونه در سایدبار وردپرس
         */
        public function add_menu() {
            add_menu_page(
                'ایران اسپید پرو',            // عنوان صفحه
                'ایران اسپید',               // نام در منو
                'manage_options',            // فقط مدیران کل دسترسی داشته باشند
                $this->plugin_name,          // اسلاگ منو
                array( $this, 'display_dashboard' ), // متد نمایش محتوا
                'dashicons-performance',     // آیکون سرعت
                2                            // جایگاه منو
            );
        }

        /**
         * بارگذاری دارایی‌ها (Assets) و تزریق توکن امنیتی به جاوااسکریپت
         */
        public function enqueue_assets( $hook ) {
            // فقط در صفحه اختصاصی افزونه لود شود
            if ( $hook != 'toplevel_page_' . $this->plugin_name ) {
                return;
            }

            // لود CSS ادمین
            wp_enqueue_style( 
                $this->plugin_name . '-css', 
                plugin_dir_url( __FILE__ ) . 'css/iran-speed-pro-admin.css', 
                array(), 
                $this->version, 
                'all' 
            );

            // لود JS ادمین
            wp_enqueue_script( 
                $this->plugin_name . '-js', 
                plugin_dir_url( __FILE__ ) . 'js/iran-speed-pro-admin.js', 
                array( 'jquery' ), 
                $this->version, 
                true 
            );

            /**
             * تزریق داده‌های PHP به JS (بسیار مهم)
             * تولید Nonce برای تایید اصالت درخواست‌های AJAX
             */
            wp_localize_script( $this->plugin_name . '-js', 'isp_ajax_obj', array(
                'ajax_url' => admin_url( 'admin-ajax.php' ),
                'nonce'    => wp_create_nonce( 'isp_security_nonce_v2' )
            ) );
        }

        /**
         * فراخوانی ویو (View) داشبورد و ارسال داده‌های زنده دیتابیس به آن
         */
        public function display_dashboard() {
            // دریافت آمار از کلاس بهینه‌ساز دیتابیس (موجود در includes)
            $db_optimizer = new ISP_DB_Optimizer();
            $db_stats     = $db_optimizer->get_db_stats();

            // لود فایل ویو
            require_once plugin_dir_path( __FILE__ ) . 'views/dashboard.php';
        }

        /**
         * ثبت اکشن‌های AJAX برای وردپرس
         */
        private function register_ajax_actions() {
            // عملیات پاکسازی کش
            add_action( 'wp_ajax_isp_action_clear_cache', array( $this, 'handle_ajax_clear_cache' ) );
            
            // عملیات بهینه‌سازی دیتابیس
            add_action( 'wp_ajax_isp_action_optimize_db', array( $this, 'handle_ajax_optimize_db' ) );
        }

        /**
         * پردازش امن درخواست پاکسازی کش
         */
        public function handle_ajax_clear_cache() {
            // ۱. بررسی اعتبار Nonce (امنیت در برابر حملات CSRF)
            check_ajax_referer( 'isp_security_nonce_v2', 'security' );

            // ۲. بررسی سطح دسترسی کاربر
            if ( ! current_user_can( 'manage_options' ) ) {
                wp_send_json_error( 'دسترسی غیرمجاز!' );
            }

            // ۳. اجرای عملیات از طریق کلاس Cache Handler
            $cache_manager = new ISP_Cache_Handler();
            if ( $cache_manager->clear_all_cache() ) {
                ISP_Logger::log( "All caches cleared by admin." );
                wp_send_json_success( 'تمامی حافظه‌های پنهان با موفقیت تخلیه شدند.' );
            } else {
                wp_send_json_error( 'خطا در عملیات پاکسازی کش.' );
            }
        }

        /**
         * پردازش امن درخواست بهینه‌سازی دیتابیس
         */
        public function handle_ajax_optimize_db() {
            // ۱. بررسی اعتبار Nonce
            check_ajax_referer( 'isp_security_nonce_v2', 'security' );

            // ۲. بررسی سطح دسترسی
            if ( ! current_user_can( 'manage_options' ) ) {
                wp_send_json_error( 'شما اجازه انجام این کار را ندارید.' );
            }

            // ۳. اجرای جراحی دیتابیس
            $db_manager = new ISP_DB_Optimizer();
            if ( $db_manager->optimize_all() ) {
                ISP_Logger::log( "Database optimized successfully." );
                wp_send_json_success( 'دیتابیس با موفقیت جراحی و بهینه‌سازی شد.' );
            } else {
                wp_send_json_error( 'خطایی در بهینه‌سازی جداول رخ داد.' );
            }
        }
    }
}