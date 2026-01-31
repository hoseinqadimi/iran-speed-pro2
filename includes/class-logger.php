<?php
/**
 * کلاس ثبت گزارشات و لاگ‌های افزونه
 * مسیر: includes/class-logger.php
 */

if (!defined('ABSPATH')) {
    exit;
}

if (!class_exists('ISP_Logger')) {

    class ISP_Logger {

        private static $log_file;

        /**
         * تنظیم مسیر فایل لاگ در پوشه آپلود وردپرس
         */
        public static function init() {
            $upload_dir = wp_upload_dir();
            self::$log_file = $upload_dir['basedir'] . '/isp-logs.log';
        }

        /**
         * ثبت یک پیام در فایل لاگ
         * @param string $message متن گزارش
         * @param string $type نوع گزارش (info, error, success)
         */
        public static function log($message, $type = 'info') {
            self::init();
            $timestamp = current_time('mysql');
            $formatted_message = "[{$timestamp}] [{$type}]: {$message}" . PHP_EOL;
            
            error_log($formatted_message, 3, self::$log_file);
        }

        /**
         * خواندن آخرین گزارش‌ها برای نمایش در پنل مدیریت
         */
        public static function get_logs($limit = 50) {
            self::init();
            if (!file_exists(self::$log_file)) {
                return 'هنوز گزارشی ثبت نشده است.';
            }
            $logs = file(self::$log_file);
            return array_slice(array_reverse($logs), 0, $limit);
        }

        /**
         * پاکسازی کامل فایل لاگ
         */
        public static function clear_logs() {
            self::init();
            if (file_exists(self::$log_file)) {
                unlink(self::$log_file);
            }
        }
    }
}