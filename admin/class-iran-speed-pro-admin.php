<?php
/**
 * Iran Speed Pro 2 - Admin Area Controller
 * این فایل مسئول مدیریت تمامی عملیات‌های سمت پیشخوان، ثبت منوها و پردازش درخواست‌های AJAX است.
 * * @package    Iran_Speed_Pro
 * @subpackage Iran_Speed_Pro/admin
 * @author     Hosein Qadimi
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // جلوگیری از دسترسی مستقیم
}

if ( ! class_exists( 'ISP_Admin' ) ) {

    class ISP_Admin {

        /**
         * شناسه افزونه
         */
        private $plugin_name;

        /**
         * نسخه فعلی
         */
        private $version;

        /**
         * سازنده کلاس ادمین
         */
        public function __construct( $plugin_name, $version ) {
            $this->plugin_name = $plugin_name;
            $this->version     = $version;

            // ثبت عملیات‌های AJAX (هم برای مدیران و هم کاربران دارای سطح دسترسی مناسب)
            $this->register_ajax_callbacks();
        }

        /**
         * ایجاد منوی اصلی و زیرمنوها در پیشخوان وردپرس
         */
        public function add_menu() {
            add_menu_page(
                'ایران اسپید پرو',            // عنوان صفحه مرورگر
                'ایران اسپید',               // نام منو در پیشخوان
                'manage_options',            // سطح دسترسی لازم (مدیر کل)
                $this->plugin_name,          // اسلاگ صفحه
                array( $this, 'display_dashboard' ), // تابع نمایش محتوا
                'dashicons-performance',     // آیکون منو
                2                            // اولویت قرارگیری در لیست منوها
            );
        }

        /**
         * بارگذاری فایل‌های CSS و JS اختصاصی ادمین
         * این فایل‌ها فقط در صفحه اختصاصی افزونه لود می‌شوند تا تداخلی ایجاد نکنند.
         */
        public function enqueue_assets( $hook ) {
            // اطمینان از اینکه اسکریپت‌ها فقط در صفحه افزونه ما لود شوند
            if ( $hook != 'toplevel_page_' . $this->plugin_name ) {
                return;
            }

            // لود استایل اختصاصی
            wp_enqueue_style( 
                $this->plugin_name . '-admin-css', 
                plugin_dir_url( __FILE__ ) . 'css/iran-speed-pro-admin.css', 
                array(), 
                $this->version, 
                'all' 
            );

            // لود اسکریپت جاوااسکریپت با پیش‌نیاز jQuery
            wp_enqueue_script( 
                $this->plugin_name . '-admin-js', 
                plugin_dir_url( __FILE__ ) . 'js/iran-speed-pro-admin.js', 
                array( 'jquery' ), 
                $this->version, 
                true 
            );

            // ارسال توکن امنیتی (Nonce) و آدرس AJAX به فایل جاوااسکریپت
            wp_localize_script( $this->plugin_name . '-admin-js', 'isp_ajax_obj', array(
                'ajax_url' => admin_url( 'admin-ajax.php' ),
                'nonce'    => wp_create_nonce( 'isp_admin_secure_nonce' )
            ) );
        }

        /**
         * فراخوانی و نمایش صفحه اصلی داشبورد (View)
         */
        public function display_dashboard() {
            // نمونه‌سازی از کلاس‌های مورد نیاز برای دریافت آمار لحظه‌ای
            $db_optimizer = new ISP_DB_Optimizer();
            $db_stats     = $db_optimizer->get_db_stats(); // دریافت آمار حجم و سربار دیتابیس

            $monitor      = new ISP_Resource_Monitor();
            $server_info  = $monitor->get_server_status(); // دریافت اطلاعات سرور

            // فراخوانی فایل ویو
            require_once plugin_dir_path( __FILE__ ) . 'views/dashboard.php';
        }

        /**
         * ثبت اکشن‌های AJAX برای دریافت درخواست‌های ارسالی از سمت کلاینت
         */
        private function register_ajax_callbacks() {
            // اکشن پاکسازی کش
            add_action( 'wp_ajax_isp_process_clear_cache', array( $this, 'ajax_handle_clear_cache' ) );
            
            // اکشن بهینه‌سازی دیتابیس
            add_action( 'wp_ajax_isp_process_db_optimization', array( $this, 'ajax_handle_db_optimization' ) );
        }

        /**
         * هندلر AJAX برای پاکسازی کش
         */
        public function ajax_handle_clear_cache() {
            // بررسی توکن امنیتی برای جلوگیری از حملات CSRF
            check_ajax_referer( 'isp_admin_secure_nonce', 'security' );

            if ( ! current_user_can( 'manage_options' ) ) {
                wp_send_json_error( 'شما اجازه دسترسی به این عملیات را ندارید.' );
            }

            $cache_handler = new ISP_Cache_Handler();
            $result        = $cache_handler->clear_all_cache();

            if ( $result ) {
                ISP_Logger::log( "User ID " . get_current_user_id() . " cleared all caches." );
                wp_send_json_success( 'تمامی حافظه‌های پنهان با موفقیت تخلیه شدند.' );
            } else {
                wp_send_json_error( 'پاکسازی کش با خطا مواجه شد.' );
            }
        }

        /**
         * هندلر AJAX برای جراحی و بهینه‌سازی دیتابیس
         */
        public function ajax_handle_db_optimization() {
            // بررسی توکن امنیتی
            check_ajax_referer( 'isp_admin_secure_nonce', 'security' );

            if ( ! current_user_can( 'manage_options' ) ) {
                wp_send_json_error( 'دسترسی غیرمجاز!' );
            }

            $optimizer = new ISP_DB_Optimizer();
            $optimized = $optimizer->optimize_all();

            if ( $optimized ) {
                ISP_Logger::log( "Database surgical optimization completed successfully." );
                wp_send_json_success( 'دیتابیس شما با موفقیت جراحی و بهینه‌سازی شد.' );
            } else {
                wp_send_json_error( 'خطایی در حین بهینه‌سازی جداول رخ داد.' );
            }
        }
    }
}