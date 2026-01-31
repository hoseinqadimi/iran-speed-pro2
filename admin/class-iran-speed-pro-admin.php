<?php
/**
 * کلاس اصلی مدیریت بخش پیشخوان (Admin Logic)
 * مسیر: admin/class-iran-speed-pro-admin.php
 */

if (!defined('ABSPATH')) {
    exit;
}

if (!class_exists('ISP_Admin')) {

    class ISP_Admin {

        private $plugin_name;
        private $version;

        public function __construct($plugin_name, $version) {
            $this->plugin_name = $plugin_name;
            $this->version = $version;
            
            // ثبت اکشن‌های AJAX برای دکمه‌های داشبورد
            $this->register_ajax_hooks();
        }

        /**
         * ثبت منوی اصلی در پیشخوان
         */
        public function add_menu() {
            add_menu_page(
                'ایران اسپید پرو',
                'ایران اسپید',
                'manage_options',
                $this->plugin_name,
                array($this, 'display_dashboard'),
                'dashicons-performance',
                2
            );
        }

        /**
         * لود کردن استایل‌ها و اسکریپت‌ها
         */
        public function enqueue_assets($hook) {
            if ($hook != 'toplevel_page_' . $this->plugin_name) {
                return;
            }

            // لود CSS اختصاصی
            wp_enqueue_style($this->plugin_name . '-style', plugin_dir_url(__FILE__) . 'css/iran-speed-pro-admin.css', array(), $this->version);

            // لود JS و ارسال متغیرهای لازم برای AJAX
            wp_enqueue_script($this->plugin_name . '-script', plugin_dir_url(__FILE__) . 'js/iran-speed-pro-admin.js', array('jquery'), $this->version, true);

            // ارسال کد امنیتی Nonce به فایل JS برای امنیت درخواست‌ها
            wp_localize_script($this->plugin_name . '-script', 'isp_ajax_obj', array(
                'ajax_url' => admin_url('admin-ajax.php'),
                'nonce'    => wp_create_nonce('isp_security_nonce')
            ));
        }

        /**
         * فراخوانی فایل View (داشبورد)
         */
        public function display_dashboard() {
            // دریافت اطلاعات برای نمایش زنده (مثال: حجم دیتابیس)
            $db_optimizer = new ISP_DB_Optimizer();
            $db_stats = $db_optimizer->get_db_stats();
            
            require_once plugin_dir_path(__FILE__) . 'views/dashboard.php';
        }

        /**
         * ثبت اکشن‌های AJAX
         */
        private function register_ajax_hooks() {
            // اکشن برای پاکسازی کش
            add_action('wp_ajax_isp_clear_cache', array($this, 'handle_cache_clearing'));
            // اکشن برای بهینه‌سازی دیتابیس
            add_action('wp_ajax_isp_optimize_db', array($this, 'handle_db_optimization'));
        }

        /**
         * عملیات پاکسازی کش (پاسخ به AJAX)
         */
        public function handle_cache_clearing() {
            check_ajax_referer('isp_security_nonce', 'security');

            if (!current_user_can('manage_options')) {
                wp_send_json_error('شما سطح دسترسی کافی ندارید.');
            }

            $cache = new ISP_Cache_Handler();
            if ($cache->clear_all_cache()) {
                ISP_Logger::log("عملیات پاکسازی کش با موفقیت انجام شد.");
                wp_send_json_success('تمامی حافظه‌های پنهان با موفقیت تخلیه شدند.');
            } else {
                wp_send_json_error('خطایی در پاکسازی کش رخ داد.');
            }
        }

        /**
         * عملیات بهینه‌سازی دیتابیس (پاسخ به AJAX)
         */
        public function handle_db_optimization() {
            check_ajax_referer('isp_security_nonce', 'security');

            if (!current_user_can('manage_options')) {
                wp_send_json_error('شما سطح دسترسی کافی ندارید.');
            }

            $optimizer = new ISP_DB_Optimizer();
            $result = $optimizer->optimize_all();

            if ($result) {
                ISP_Logger::log("دیتابیس سایت بهینه‌سازی و جراحی شد.");
                wp_send_json_success('دیتابیس با موفقیت جراحی و بهینه‌سازی شد.');
            } else {
                wp_send_json_error('عملیات بهینه‌سازی با شکست مواجه شد.');
            }
        }
    }
}