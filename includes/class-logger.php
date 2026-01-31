<?php
/**
 * کلاس ثبت گزارشات عملیاتی
 * مسیر: includes/class-logger.php
 */

if (!defined('ABSPATH')) {
    exit;
}

if (!class_exists('ISP_Logger')) {

    class ISP_Logger {

        private static function get_log_path() {
            $upload_dir = wp_upload_dir();
            return $upload_dir['basedir'] . '/isp-activity.log';
        }

        /**
         * ثبت لاگ جدید
         */
        public static function log($message, $level = 'INFO') {
            $time = current_time('mysql');
            $entry = "[{$time}] [{$level}]: {$message}" . PHP_EOL;
            file_put_contents(self::get_log_path(), $entry, FILE_APPEND);
        }

        /**
         * دریافت گزارش‌ها برای نمایش
         */
        public static function get_logs($count = 20) {
            $file = self::get_log_path();
            if (!file_exists($file)) return array('هنوز گزارشی ثبت نشده است.');
            
            $logs = file($file);
            return array_slice(array_reverse($logs), 0, $count);
        }

        /**
         * حذف فایل لاگ
         */
        public static function purge() {
            if (file_exists(self::get_log_path())) {
                unlink(self::get_log_path());
            }
        }
    }
}