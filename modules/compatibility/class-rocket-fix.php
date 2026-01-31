<?php
/**
 * حل تداخلات با افزونه WP Rocket
 * وظیفه: مدیریت اولویت‌بندی در بهینه‌سازی‌های مشترک.
 */

if (!defined('ABSPATH')) exit;

class ISP_Rocket_Fix {

    /**
     * بررسی وجود دبلی‌پی راکت و اجرای تنظیمات سازگاری
     */
    public function init() {
        if (defined('WP_ROCKET_VERSION')) {
            add_filter('rocket_exclude_js', array($this, 'exclude_isp_from_rocket'));
            add_filter('rocket_delay_js_exclusions', array($this, 'exclude_isp_from_delay'));
            
            // اگر راکت فعال باشد، اولویت را به راکت می‌دهیم تا تداخل ایجاد نشود
            add_action('wp_head', array($this, 'log_compatibility_status'), 1);
        }
    }

    /**
     * جلوگیری از جابجایی اسکریپت‌های ایران اسپید توسط WP Rocket
     */
    public function exclude_isp_from_rocket($excluded_js) {
        $excluded_js[] = '/iran-speed-pro/admin/js/(.*).js';
        return $excluded_js;
    }

    /**
     * جلوگیری از تاخیر مضاعف روی اسکریپت‌های مدیریت افزونه
     */
    public function exclude_isp_from_delay($excluded_delay_js) {
        $excluded_delay_js[] = 'isp-main-script';
        return $excluded_delay_js;
    }

    /**
     * ثبت وضعیت سازگاری در کدهای هدر (برای عیب‌یابی)
     */
    public function log_compatibility_status() {
        echo "\n\n";
    }
}