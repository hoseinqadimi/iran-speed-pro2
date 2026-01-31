<?php
/**
 * حل تداخلات با افزونه LiteSpeed Cache
 * وظیفه: هماهنگی سیستم Delay JS و Minify با کش سطح سرور لایت‌اسپید.
 */

if (!defined('ABSPATH')) exit;

class ISP_Litespeed_Fix {

    /**
     * اجرای هوک‌های هماهنگ‌سازی
     */
    public function init() {
        // اگر افزونه لایت‌اسپید فعال بود، وارد عمل شو
        if (defined('LSCWP_V')) {
            add_filter('litespeed_optimize_js_excludes', array($this, 'exclude_isp_scripts'));
            add_action('litespeed_control_finalize', array($this, 'disable_litespeed_minification_if_needed'));
        }
    }

    /**
     * جلوگیری از فشرده‌سازی مجدد اسکریپت‌های ایران اسپید توسط لایت‌اسپید
     */
    public function exclude_isp_scripts($excludes) {
        $isp_scripts = array(
            'isp-main-script',
            'isp-js-loader'
        );
        return array_merge((array)$excludes, $isp_scripts);
    }

    /**
     * مدیریت تداخل در فشرده‌سازی HTML
     */
    public function disable_litespeed_minification_if_needed() {
        // اگر سیستم Minify ما فعال است، برای جلوگیری از Double Minification، نسخه لایت‌اسپید را کنترل کن
        if (class_exists('ISP_Minifier')) {
            do_action('litespeed_debug', 'ISP: Detected internal minifier, adjusting Litespeed settings.');
            // در اینجا می‌توان دستورات پاکسازی کش لایت‌اسپید را در صورت تغییر تنظیمات صادر کرد
        }
    }
}