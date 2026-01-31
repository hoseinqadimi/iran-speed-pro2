<?php
/**
 * حالت بحرانی (Panic Mode)
 * وظیفه: قطع سریع تمام اتصالات و اسکریپت‌های غیرحیاتی در شرایط اضطراری.
 */

if (!defined('ABSPATH')) exit;

class ISP_Network_Panic_Mode {

    /**
     * بررسی فعال بودن حالت بحرانی و اجرای محدودیت‌ها
     */
    public function init() {
        // دریافت تنظیمات از دیتابیس
        $settings = get_option('isp_settings');

        if (isset($settings['panic_mode']) && $settings['panic_mode'] === 'on') {
            // ۱. قطع کامل دسترسی به REST API برای همه (حتی مدیران در لحظه بحران)
            add_filter('rest_authentication_errors', array($this, 'lockdown_rest_api'), 99);

            // ۲. غیرفعال کردن تمام اسکریپت‌های خارجی و اموجی‌ها برای سبک شدن حداکثری
            add_action('wp_enqueue_scripts', array($this, 'strip_scripts'), 999);
            
            // ۳. غیرفعال کردن XML-RPC (راه نفوذ و فشار رایج)
            add_filter('xmlrpc_enabled', '__return_false');
        }
    }

    /**
     * قفل کامل API
     */
    public function lockdown_rest_api() {
        return new WP_Error('panic_active', 'سایت در وضعیت نگهداری اضطراری است.', array('status' => 503));
    }

    /**
     * حذف اسکریپت‌های سنگین در حالت بحران
     */
    public function strip_scripts() {
        // حذف اسکریپت‌های پیش‌فرض که در لحظه بحران لودشان ضروری نیست
        wp_dequeue_script('devicepx'); // Jetpack
        wp_dequeue_script('wp-embed');
        remove_action('wp_head', 'print_emoji_detection_script', 7);
        remove_action('wp_print_styles', 'print_emoji_styles');
    }
}