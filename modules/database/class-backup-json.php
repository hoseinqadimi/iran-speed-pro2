<?php
/**
 * پشتیبان‌گیری از جداول در قالب JSON
 * وظیفه: استخراج داده‌های جداول قبل از حذف، برای امنیت کامل.
 */

if (!defined('ABSPATH')) exit;

class ISP_Backup_Json {

    /**
     * مسیر ذخیره‌سازی فایل‌های بک‌آپ
     */
    private $backup_path;

    public function __construct() {
        $upload_dir = wp_upload_dir();
        // استفاده از پوشه امنی که در فایل Activator ساختیم
        $this->backup_path = $upload_dir['basedir'] . '/isp-storage/backups/';
        
        if (!file_exists($this->backup_path)) {
            wp_mkdir_p($this->backup_path);
        }
    }

    /**
     * تهیه بک‌آپ از یک جدول خاص
     */
    public function backup_table($table_name) {
        global $wpdb;

        // ۱. دریافت تمام داده‌های جدول
        $results = $wpdb->get_results("SELECT * FROM $table_name", ARRAY_A);
        
        if (empty($results)) {
            return false;
        }

        // ۲. تبدیل داده‌ها به فرمت JSON
        $json_data = json_encode($results, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

        // ۳. نام‌گذاری فایل (نام جدول + تاریخ)
        $file_name = $table_name . '_' . date('Y-m-d_H-i-s') . '.json';
        
        // ۴. ذخیره در مسیر امن
        return file_put_contents($this->backup_path . $file_name, $json_data);
    }
}