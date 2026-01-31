<?php
/**
 * کلاس بررسی اصالت و سلامت فایل‌های هسته
 * وظیفه: اطمینان از عدم تغییر فایل‌های وردپرس توسط بدافزارها
 * مسیر: includes/class-integrity-checker.php
 */

if (!defined('ABSPATH')) {
    exit;
}

if (!class_exists('ISP_Integrity_Checker')) {

    class ISP_Integrity_Checker {

        /**
         * بررسی فایل‌های اصلی وردپرس با استفاده از API رسمی
         * @return array|bool لیست فایل‌های تغییر یافته یا true در صورت سلامت کامل
         */
        public function check_core_integrity() {
            include_once ABSPATH . 'wp-admin/includes/update.php';
            
            $version = get_bloginfo('version');
            $locale  = get_locale();
            
            // فراخوانی چک‌سوم‌های رسمی از سرور وردپرس
            $checksums = get_core_checksums($version, $locale);

            if (!$checksums) {
                return false;
            }

            $modified_files = array();

            foreach ($checksums as $file => $checksum) {
                // فقط بررسی فایل‌های روت و پوشه‌های اصلی (بدون wp-content)
                if (strpos($file, 'wp-content') === 0) continue;

                $file_path = ABSPATH . $file;

                if (!file_exists($file_path)) {
                    $modified_files[] = array(
                        'file' => $file,
                        'status' => 'missing'
                    );
                    continue;
                }

                if (md5_file($file_path) !== $checksum) {
                    $modified_files[] = array(
                        'file' => $file,
                        'status' => 'modified'
                    );
                }
            }

            return empty($modified_files) ? true : $modified_files;
        }

        /**
         * دریافت آخرین زمان تغییر در فایل .htaccess
         */
        public function get_htaccess_status() {
            $path = ABSPATH . '.htaccess';
            if (file_exists($path)) {
                return date("Y-m-d H:i:s", filemtime($path));
            }
            return 'فایل یافت نشد';
        }
    }
}