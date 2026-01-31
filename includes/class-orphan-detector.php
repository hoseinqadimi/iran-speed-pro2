<?php
/**
 * کلاس شناسایی جداول یتیم (Orphan)
 * وظیفه: مقایسه جداول دیتابیس با افزونه‌های نصب شده و شناسایی موارد اضافی
 * مسیر: includes/class-orphan-detector.php
 */

if (!defined('ABSPATH')) {
    exit;
}

if (!class_exists('ISP_Orphan_Detector')) {

    class ISP_Orphan_Detector {

        private $wpdb;

        public function __construct() {
            global $wpdb;
            $this->wpdb = $wpdb;
        }

        /**
         * شناسایی جداول یتیم در دیتابیس
         * @return array لیست نام جداول یتیم شناسایی شده
         */
        public function get_orphan_tables() {
            $all_tables = $this->get_all_wordpress_tables();
            $active_prefixes = $this->get_active_plugin_prefixes();
            $orphan_tables = array();

            // جداول استاندارد خود وردپرس که نباید دست بخورند
            $core_tables = array(
                'commentmeta', 'comments', 'links', 'options', 'postmeta', 
                'posts', 'termmeta', 'terms', 'term_relationships', 
                'term_taxonomy', 'usermeta', 'users'
            );

            foreach ($all_tables as $table) {
                // حذف پیشوند اصلی (مثلاً wp_) برای بررسی دقیق‌تر
                $unprefixed_table = str_replace($this->wpdb->prefix, '', $table);
                
                // اگر جدول جزو هسته وردپرس نبود، بررسی کن آیا به افزونه‌ای تعلق دارد یا خیر
                if (!in_array($unprefixed_table, $core_tables)) {
                    if (!$this->is_table_in_use($unprefixed_table, $active_prefixes)) {
                        $orphan_tables[] = $table;
                    }
                }
            }

            return $orphan_tables;
        }

        /**
         * دریافت لیست تمام جداول موجود در دیتابیس فعلی
         */
        private function get_all_wordpress_tables() {
            $query = $this->wpdb->prepare("SHOW TABLES LIKE %s", $this->wpdb->prefix . '%');
            return $this->wpdb->get_col($query);
        }

        /**
         * دریافت پیشوندهای شناخته شده از روی امضاهای دیتابیس (Signatures)
         */
        private function get_active_plugin_prefixes() {
            // در اینجا می‌توان از فایل database-signatures.json که در گیت‌هابت داری استفاده کرد
            $signatures_path = ISP_PATH . 'includes/database-signatures.json';
            if (file_exists($signatures_path)) {
                $data = json_decode(file_get_contents($signatures_path), true);
                return isset($data['prefixes']) ? $data['prefixes'] : array();
            }
            return array();
        }

        /**
         * بررسی اینکه آیا نام جدول با هیچ‌کدام از پیشوندهای فعال مطابقت دارد یا خیر
         */
        private function is_table_in_use($table_name, $prefixes) {
            foreach ($prefixes as $prefix) {
                if (strpos($table_name, $prefix) === 0) {
                    return true;
                }
            }
            return false;
        }
    }
}