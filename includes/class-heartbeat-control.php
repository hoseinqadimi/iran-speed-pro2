<?php
/**
 * کلاس کنترل ضربان قلب وردپرس (Heartbeat API)
 * وظیفه: کاهش مصرف CPU با مدیریت فواصل زمانی درخواست‌های پس‌زمینه
 * مسیر: includes/class-heartbeat-control.php
 */

if (!defined('ABSPATH')) {
    exit;
}

if (!class_exists('ISP_Heartbeat_Control')) {

    class ISP_Heartbeat_Control {

        /**
         * اعمال تنظیمات کنترل ضربان قلب
         * @param string $behavior وضعیت (disable, modify)
         * @param int $interval فاصله زمانی جدید (15 تا 120 ثانیه)
         */
        public function apply_settings($behavior = 'modify', $interval = 60) {
            if ($behavior === 'disable') {
                add_action('init', array($this, 'disable_heartbeat'), 1);
            } else {
                add_filter('heartbeat_settings', function($settings) use ($interval) {
                    $settings['interval'] = $interval;
                    return $settings;
                });
            }
        }

        /**
         * غیرفعال کردن کامل Heartbeat در تمام بخش‌ها
         */
        public function disable_heartbeat() {
            wp_deregister_script('heartbeat');
        }

        /**
         * محدود کردن ضربان قلب فقط به صفحات ویرایش نوشته
         * برای جلوگیری از مصرف بیهوده در داشبورد اصلی
         */
        public function limit_to_post_edit() {
            global $pagenow;
            if ($pagenow !== 'post.php' && $pagenow !== 'post-new.php') {
                $this->disable_heartbeat();
            }
        }
    }
}