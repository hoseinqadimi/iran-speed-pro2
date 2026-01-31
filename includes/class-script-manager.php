<?php
/**
 * کلاس مدیریت اسکریپت‌ها و استایل‌ها
 * وظیفه: Dequeue کردن فایل‌های CSS و JS غیرضروری
 * مسیر: includes/class-script-manager.php
 */

if (!defined('ABSPATH')) {
    exit;
}

if (!class_exists('ISP_Script_Manager')) {

    class ISP_Script_Manager {

        /**
         * دریافت لیست تمام اسکریپت‌های لود شده در صفحه فعلی
         * (برای استفاده در تنظیمات افزونه)
         */
        public function get_enqueued_scripts() {
            global $wp_scripts;
            return $wp_scripts->queue;
        }

        /**
         * غیرفعال کردن اسکریپت‌های سنگین یا غیرضروری
         * @param array $handles لیست شناسه اسکریپت‌ها (مانند 'contact-form-7')
         */
        public function dequeue_scripts($handles) {
            foreach ($handles as $handle) {
                wp_dequeue_script($handle);
                wp_deregister_script($handle);
            }
        }

        /**
         * غیرفعال کردن استایل‌های سنگین (CSS)
         * @param array $handles
         */
        public function dequeue_styles($handles) {
            foreach ($handles as $handle) {
                wp_dequeue_style($handle);
                wp_deregister_style($handle);
            }
        }

        /**
         * حذف Emojiهای پیش‌فرض وردپرس (یکی از دلایل کندی لود)
         */
        public function remove_wp_emojis() {
            remove_action('wp_head', 'print_emoji_detection_script', 7);
            remove_action('admin_print_scripts', 'print_emoji_detection_script');
            remove_action('wp_print_styles', 'print_emoji_styles');
            remove_action('admin_print_styles', 'print_emoji_styles');
            remove_filter('the_content_feed', 'wp_staticize_emoji');
            remove_filter('comment_text_rss', 'wp_staticize_emoji');
            remove_filter('wp_mail', 'wp_staticize_emoji_for_email');
        }
    }
}