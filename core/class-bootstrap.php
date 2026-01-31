<?php
/**
 * مدیریت لودر و چرخه حیات افزونه (Bootstrap)
 * وظیفه: مدیریت نمونه‌ها و اجرای ماژول‌ها بر اساس تنظیمات.
 */

if (!defined('ABSPATH')) exit;

class ISP_Bootstrap {

    /**
     * نگهداری تنها نمونه کلاس (Singleton)
     */
    private static $instance = null;

    /**
     * دریافت نمونه کلاس
     */
    public static function get_instance() {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * سازنده کلاس (خصوصی برای جلوگیری از ایجاد نمونه جدید)
     */
    private function __construct() {}

    /**
     * راه‌اندازی اولیه و ثبت هوک‌ها
     */
    public function init() {
        add_action('plugins_loaded', array($this, 'run_modules'));
    }

    /**
     * اجرای ماژول‌ها بر اساس وضعیت فعال بودن در دیتابیس
     */
    public function run_modules() {
        $settings = get_option('isp_settings', array());

        // ۱. ماژول شبکه و امنیت (Network)
        if ($this->is_active($settings, 'api_firewall')) {
            $api_firewall = new ISP_Network_API_Firewall();
            $api_firewall->init();
        }
        
        $heartbeat = new ISP_Network_Heartbeat_Control();
        $heartbeat->init();

        if ($this->is_active($settings, 'panic_mode')) {
            $panic = new ISP_Network_Panic_Mode();
            $panic->init();
        }

        // ۲. ماژول بهینه‌ساز (Optimizer)
        if ($this->is_active($settings, 'minify_html')) {
            $minifier = new ISP_Minifier();
            $minifier->init();
        }

        if ($this->is_active($settings, 'delay_js')) {
            $delay_js = new ISP_Delay_JS();
            $delay_js->init();
        }

        $preloader = new ISP_Preloader();
        $preloader->init();

        // ۳. ماژول دیتابیس (Database)
        $db_optimizer = new ISP_DB_Optimizer();
        // این بخش معمولاً توسط کرون جاب یا دستی اجرا می‌شود

        // ۴. ماژول سازگاری (Compatibility)
        $litespeed = new ISP_Litespeed_Fix();
        $litespeed->init();

        $rocket = new ISP_Rocket_Fix();
        $rocket->init();
    }

    /**
     * بررسی وضعیت فعال بودن یک ویژگی
     */
    private function is_active($settings, $key) {
        return (isset($settings[$key]) && $settings[$key] === 'on');
    }
}