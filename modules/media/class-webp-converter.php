<?php
/**
 * مبدل تصاویر به WebP
 * وظیفه: کاهش حجم تصاویر بدون افت کیفیت محسوس.
 */

if (!defined('ABSPATH')) exit;

class ISP_Webp_Converter {

    /**
     * بررسی توانایی سرور برای پردازش تصویر
     */
    private function can_convert() {
        return function_exists('imagewebp') || class_exists('Imagick');
    }

    /**
     * تبدیل تصویر به فرمت WebP
     */
    public function convert($file_path, $quality = 80) {
        if (!$this->can_convert() || !file_exists($file_path)) return false;

        $info = getimagesize($file_path);
        $output_path = preg_replace('/\.(jpe?g|png)$/i', '.webp', $file_path);

        if ($info['mime'] == 'image/jpeg') {
            $image = imagecreatefromjpeg($file_path);
        } elseif ($info['mime'] == 'image/png') {
            $image = imagecreatefrompng($file_path);
            imagepalettetotruecolor($image);
        } else {
            return false;
        }

        imagewebp($image, $output_path, $quality);
        imagedestroy($image);
        
        return $output_path;
    }
}