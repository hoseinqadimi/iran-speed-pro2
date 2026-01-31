<?php
/**
 * کلاس عملیاتی بهینه‌سازی دیتابیس
 * وظیفه: اجرای دستورات حذف جداول اضافی و بهینه‌سازی جداول اصلی
 * مسیر: includes/class-db-optimizer.php
 */

if (!defined('ABSPATH')) {
    exit;
}

if (!class_exists('ISP_DB_Optimizer')) {

    class ISP_DB_Optimizer {

        private $wpdb;

        public function __construct() {
            global $wpdb;
            $this->wpdb = $wpdb;
        }

        /**
         * حذف فیزیکی یک جدول از دیتابیس
         * @param string $table_name نام کامل جدول با پیشوند
         * @return bool نتیجه عملیات
         */
        public function drop_orphan_table($table_name) {
            // امنیت: بررسی اینکه نام جدول با پیشوند وردپرس شروع شود
            if (strpos($table_name, $this->wpdb->prefix) !== 0) {
                return false;
            }

            $query = "DROP TABLE IF EXISTS `$table_name`";
            return $this->wpdb->query($query) !== false;
        }

        /**
         * بهینه‌سازی و یکپارچه‌سازی یک جدول (Defragmentation)
         * @param string $table_name
         */
        public function optimize_table($table_name) {
            $query = "OPTIMIZE TABLE `$table_name`";
            return $this->wpdb->query($query) !== false;
        }

        /**
         * دریافت حجم کل دیتابیس به صورت خوانا
         */
        public function get_database_size() {
            $query = $this->wpdb->prepare(
                "SELECT SUM(data_length + index_length) FROM information_schema.TABLES WHERE table_schema = %s",
                DB_NAME
            );
            $size = $this->wpdb->get_var($query);
            return size_format($size, 2);
        }
    }
}