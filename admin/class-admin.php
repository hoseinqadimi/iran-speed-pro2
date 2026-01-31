<?php
/**
 * کلاس اصلی مدیریت (Admin)
 * وظیفه: ایجاد منوها، لود کردن استایل‌ها و مدیریت یکپارچه تب‌های پنل افزونه.
 */

if (!defined('ABSPATH')) {
    exit;
}

class ISP_Admin {

    /**
     * نام اختصاصی افزونه برای شناسه منوها
     */
    private $plugin_name;

    /**
     * نسخه فعلی افزونه
     */
    private $version;

    /**
     * سازنده کلاس برای مقداردهی اولیه
     */
    public function __construct() {
        $this->plugin_name = 'iran-speed-pro';
        $this->version = ISP_VERSION;
    }

    /**
     * ثبت قلاب‌های (Hooks) مربوط به بخش مدیریت
     */
    public function init() {
        // ۱. اضافه کردن منوی اصلی به پیشخوان وردپرس
        add_action('admin_menu', array($this, 'add_plugin_admin_menu'));

        // ۲. فراخوانی استایل‌ها و اسکریپت‌های اختصاصی پنل ادمین
        add_action('admin_enqueue_scripts', array($this, 'enqueue_styles'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_scripts'));

        // ۳. ثبت تنظیمات در دیتابیس (Settings API)
        add_action('admin_init', array($this, 'register_isp_settings'));
    }

    /**
     * ایجاد منوی اصلی ایران اسپید پرو در سایدبار ادمین
     */
    public function add_plugin_admin_menu() {
        add_menu_page(
            'ایران اسپید پرو',         // عنوان صفحه (Title)
            'ایران اسپید',            // نام در منو (Menu Label)
            'manage_options',        // سطح دسترسی (مدیر کل)
            $this->plugin_name,      // اسلاگ/شناسه منو
            array($this, 'display_plugin_admin_page'), // تابع رندر کردن محتوا
            'dashicons-performance', // آیکون سرعت‌سنج
            2                        // موقعیت در لیست منو
        );
    }

    /**
     * ثبت گروه تنظیمات برای ذخیره‌سازی آپشن‌ها
     */
    public function register_isp_settings() {
        register_setting('isp_settings_group', 'isp_settings');
    }

    /**
     * لود کردن فایل‌های CSS برای ظاهر مدرن پنل
     */
    public function enqueue_styles($hook) {
        // اطمینان از اینکه استایل‌ها فقط در صفحه افزونه لود شوند
        if ($hook != 'toplevel_page_' . $this->plugin_name) {
            return;
        }

        wp_enqueue_style(
            $this->plugin_name . '-modern-ui',
            ISP_URL . 'admin/css/modern-ui.css',
            array(),
            $this->version,
            'all'
        );
    }

    /**
     * لود کردن فایل‌های JavaScript برای تعاملات ایجکسی و تب‌ها
     */
    public function enqueue_scripts($hook) {
        if ($hook != 'toplevel_page_' . $this->plugin_name) {
            return;
        }

        // اسکریپت اصلی مدیریت تب‌ها و کلیک‌ها
        wp_enqueue_script(
            $this->plugin_name . '-main-script',
            ISP_URL . 'admin/js/main-script.js',
            array('jquery'),
            $this->version,
            true // لود در فوتر
        );

        // اسکریپت مقداردهی نمودارهای مانیتورینگ
        wp_enqueue_script(
            $this->plugin_name . '-charts',
            ISP_URL . 'admin/js/charts-init.js',
            array($this->plugin_name . '-main-script'),
            $this->version,
            true
        );
    }

    /**
     * نمایش خروجی نهایی پنل مدیریت
     * این متد فایل محتوا را فراخوانی می‌کند که شامل تمام تب‌ها است.
     */
    public function display_plugin_admin_page() {
        // تمام محتوای بصری در پوشه views مدیریت می‌شود
        if (file_exists(ISP_PATH . 'admin/views/dashboard.php')) {
            require_once ISP_PATH . 'admin/views/dashboard.php';
        } else {
            echo '<div class="notice notice-error"><p>خطا: فایل نمایشی داشبورد یافت نشد.</p></div>';
        }
    }
}