<?php
/**
 * مدیریت Heartbeat API وردپرس
 * وظیفه: کاهش تعداد درخواست‌های پس‌زمینه برای آزاد کردن منابع سرور.
 */

if (!defined('ABSPATH')) exit;

class ISP_Network_Heartbeat_Control {

    /**
     * اجرای تنظیمات محدودکننده
     */
    public function init() {
        add_action('init', array($this, 'modify_heartbeat_settings'), 1);
    }

    /**
     * تنظیم فرکانس ضربان قلب وردپرس
     */
    public function modify_heartbeat_settings() {
        global $pagenow;

        // اگر در صفحه ویرایش نوشته نبودیم، کلاً این قابلیت را غیرفعال کن
        if ($pagenow != 'post.php' && $pagenow != 'post-new.php') {
            wp_deregister_script('heartbeat');
        }
    }

    /**
     * تغییر فاصله زمانی ارسال درخواست (فرکانس)
     */
    public static function set_frequency($settings) {
        // تنظیم فاصله روی ۶۰ ثانیه (استاندارد بهینه)
        $settings['interval'] = 60; 
        return $settings;
    }
}