<?php
/**
 * Iran Speed Pro 2 - Admin Controller
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
            $this->register_ajax_hooks();
        }

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

        public function enqueue_assets($hook) {
            if ($hook != 'toplevel_page_' . $this->plugin_name) {
                return;
            }

            wp_enqueue_style($this->plugin_name . '-css', plugin_dir_url(__FILE__) . 'css/iran-speed-pro-admin.css', array(), $this->version, 'all');
            wp_enqueue_script($this->plugin_name . '-js', plugin_dir_url(__FILE__) . 'js/iran-speed-pro-admin.js', array('jquery'), $this->version, true);

            // ارسال توکن امنیتی و آدرس AJAX به فایل JS
            wp_localize_script($this->plugin_name . '-js', 'isp_ajax_obj', array(
                'ajax_url' => admin_url('admin-ajax.php'),
                'nonce'    => wp_create_nonce('isp_security_nonce')
            ));
        }

        public function display_dashboard() {
            // دریافت آمار واقعی از دیتابیس برای نمایش در ویو
            $db_optimizer = new ISP_DB_Optimizer();
            $db_stats = $db_optimizer->get_db_stats(); // متدی که در کلاس DB Optimizer نوشتیم
            
            require_once plugin_dir_path(__FILE__) . 'views/dashboard.php';
        }

        private function register_ajax_hooks() {
            add_action('wp_ajax_isp_clear_cache', array($this, 'handle_cache_clearing'));
            add_action('wp_ajax_isp_optimize_db', array($this, 'handle_db_optimization'));
        }

        public function handle_cache_clearing() {
            check_ajax_referer('isp_security_nonce', 'security');
            
            if (!current_user_can('manage_options')) {
                wp_send_json_error('دسترسی غیرمجاز!');
            }

            $cache = new ISP_Cache_Handler();
            if ($cache->clear_all_cache()) {
                wp_send_json_success('حافظه کش با موفقیت تخلیه شد.');
            } else {
                wp_send_json_error('خطا در پاکسازی کش.');
            }
        }

        public function handle_db_optimization() {
            check_ajax_referer('isp_security_nonce', 'security');

            if (!current_user_can('manage_options')) {
                wp_send_json_error('دسترسی غیرمجاز!');
            }

            $optimizer = new ISP_DB_Optimizer();
            if ($optimizer->optimize_all()) {
                wp_send_json_success('دیتابیس با موفقیت بهینه‌سازی شد.');
            } else {
                wp_send_json_error('خطا در جراحی دیتابیس.');
            }
        }
    }
}