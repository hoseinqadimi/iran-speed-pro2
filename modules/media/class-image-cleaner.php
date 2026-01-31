<?php
/**
 * پاکسازی تصاویر بلااستفاده
 */

if (!defined('ABSPATH')) exit;

class ISP_Image_Cleaner {

    /**
     * پیدا کردن تصاویر بدون استفاده در دیتابیس
     */
    public function get_unused_images() {
        global $wpdb;

        // جستجو برای تصاویری که به هیچ محتوایی متصل نیستند
        $query = "
            SELECT ID, guid FROM {$wpdb->posts} 
            WHERE post_type = 'attachment' 
            AND post_parent = 0 
            AND ID NOT IN (SELECT meta_value FROM {$wpdb->postmeta} WHERE meta_key = '_thumbnail_id')
        ";

        return $wpdb->get_results($query);
    }
}