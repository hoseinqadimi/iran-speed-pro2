<?php
/**
 * کلاس تبدیل تصاویر به فرمت WebP
 * مسیر: includes/class-webp-converter.php
 */

if (!defined('ABSPATH')) {
    exit;
}

if (!class_exists('ISP_WebP_Converter')) {

    class ISP_WebP_Converter {

        /**
         * بررسی اینکه آیا سرور از WebP پشتیبانی می‌کند یا خیر
         */
        public function is_server_supported() {
            return (function_exists('imagewebp') || class_exists('Imagick'));
        }

        /**
         * اجرای عملیات تبدیل
         * @param string $source_path مسیر فایل اصلی
         * @param int $quality کیفیت تصویر (پیش‌فرض 80)
         * @return string|bool مسیر فایل جدید یا شکست عملیات
         */
        public function convert($source_path, $quality = 80) {
            if (!file_exists($source_path)) return false;

            $info = getimagesize($source_path);
            $mime = $info['mime'];
            $output_path = preg_replace('/\.(jpg|jpeg|png)$/i', '.webp', $source_path);

            // اگر فایل قبلاً تبدیل شده، دوباره انجام نده
            if (file_exists($output_path)) return $output_path;

            if ($mime == 'image/jpeg') {
                $image = imagecreatefromjpeg($source_path);
            } elseif ($mime == 'image/png') {
                $image = imagecreatefrompng($source_path);
                imagepalettetotruecolor($image);
                imagealphablending($image, true);
                imagesavealpha($image, true);
            } else {
                return false;
            }

            if (imagewebp($image, $output_path, $quality)) {
                imagedestroy($image);
                return $output_path;
            }

            return false;
        }
    }
}