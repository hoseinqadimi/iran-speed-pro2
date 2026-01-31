<?php
/**
 * کلاس شناسایی و پاکسازی تصاویر بلااستفاده
 * مسیر: includes/class-image-cleaner.php
 */

if (!defined('ABSPATH')) {
    exit;
}

if (!class_exists('ISP_Image_Cleaner')) {

    class ISP_Image_Cleaner {

        /**
         * پیدا کردن تصاویری که در کتابخانه رسانه هستند اما به هیچ محتوایی متصل نیستند
         * @return array لیست ID تصاویر بلااستفاده
         */
        public function get_unused_images() {
            global $wpdb;

            // کوئری برای پیدا کردن تصاویری که parent_id آن‌ها صفر است و در محتوای پست‌ها هم تگ نشده‌اند
            $query = "
                SELECT ID FROM {$wpdb->posts} 
                WHERE post_type = 'attachment' 
                AND post_mime_type LIKE 'image/%' 
                AND post_parent = 0 
                AND ID NOT IN (
                    SELECT meta_value FROM {$wpdb->postmeta} 
                    WHERE meta_key = '_thumbnail_id'
                )
            ";

            return $wpdb->get_col($query);
        }

        /**
         * حذف امن تصویر از وردپرس (فایل فیزیکی + متاداده‌ها)
         * @param int $image_id
         * @return bool
         */
        public function delete_image($image_id) {
            // استفاده از تابع داخلی وردپرس برای حذف کامل و امن
            return wp_delete_attachment($image_id, true) !== false;
        }

        /**
         * محاسبه حجم کل تصاویر قابل پاکسازی
         */
        public function get_total_cleanup_size($image_ids) {
            $total_size = 0;
            foreach ($image_ids as $id) {
                $file = get_attached_file($id);
                if (file_exists($file)) {
                    $total_size += filesize($file);
                }
            }
            return size_format($total_size, 2);
        }
    }
}