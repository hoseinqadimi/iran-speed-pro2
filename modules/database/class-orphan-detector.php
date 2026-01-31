<?php
/**
 * تشخیص جداول یتیم (Orphan Detector)
 * وظیفه: شناسایی جداولی که متعلق به هسته وردپرس نیستند.
 */

if (!defined('ABSPATH')) exit;

class ISP_Orphan_Detector {

    /**
     * لیست جداول استاندارد وردپرس
     */
    private $wp_core_tables = array(
        'commentmeta', 'comments', 'links', 'options', 'postmeta', 
        'posts', 'termmeta', 'terms', 'term_relationships', 
        'term_taxonomy', 'usermeta', 'users'
    );

    /**
     * اسکن دیتابیس برای پیدا کردن جداول غریبه
     */
    public function get_orphan_tables() {
        global $wpdb;
        $prefix = $wpdb->prefix;
        $all_tables = $wpdb->get_results("SHOW TABLES", ARRAY_N);
        $orphans = array();

        foreach ($all_tables as $table) {
            $table_name = $table[0];
            // حذف پیشوند برای مقایسه
            $raw_name = str_replace($prefix, '', $table_name);

            // اگر جدول جزو جداول اصلی وردپرس نبود، آن را به عنوان یتیم ثبت کن
            if (!in_array($raw_name, $this->wp_core_tables)) {
                $orphans[] = $table_name;
            }
        }

        return $orphans;
    }
}