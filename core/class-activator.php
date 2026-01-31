<?php
/**
 * کلاس فعال‌ساز (Activator)
 * وظیفه: انجام عملیات زیرساختی در لحظه فعال‌سازی افزونه.
 */

// جلوگیری از دسترسی مستقیم
if (!defined('ABSPATH')) {
    exit;
}

class ISP_Activator {

    /**
     * متد اصلی که هنگام فعال‌سازی اجرا می‌شود
     */
    public static function activate() {
        // ۱. ایجاد تنظیمات پیش‌فرض در دیتابیس
        $default_settings = array(
            'version'         => ISP_VERSION,
            'iran_shield'     => 'on',
            'heartbeat_limit' => '60',
            'webp_conversion' => 'off',
            'safe_mode'       => 'off'
        );

        // اگر تنظیمات قبلاً ذخیره نشده باشد، تنظیمات پیش‌فرض را اضافه کن
        if (!get_option('isp_settings')) {
            update_option('isp_settings', $default_settings);
        }

        // ۲. ایجاد پوشه اختصاصی برای ذخیره داده‌های حساس (بک‌آپ‌ها و لاگ‌ها)
        $upload_dir = wp_upload_dir();
        $isp_storage_path = $upload_dir['basedir'] . '/isp-storage';
        
        if (!file_exists($isp_storage_path)) {
            // ساخت پوشه با دسترسی لازم
            wp_mkdir_p($isp_storage_path);
            
            // قرار دادن فایل امنیتی برای جلوگیری از مشاهده فایل‌ها توسط دیگران
            file_put_contents($isp_storage_path . '/.htaccess', 'Deny from all');
            file_put_contents($isp_storage_path . '/index.php', '<?php // Silence is golden');
        }

        // ۳. بروزرسانی قوانین پیوندهای یکتا
        flush_rewrite_rules();
    }
}