<?php
/**
 * مانیتورینگ منابع سیستم
 * وظیفه: بررسی مصرف رم و سلامت محیط میزبانی.
 */

if (!defined('ABSPATH')) exit;

class ISP_Resource_Monitor {

    /**
     * دریافت میزان مصرف حافظه فعلی
     */
    public function get_memory_usage() {
        $memory = memory_get_usage(true);
        return size_format($memory);
    }

    /**
     * دریافت محدودیت حافظه تعریف شده در وردپرس
     */
    public function get_memory_limit() {
        return WP_MEMORY_LIMIT;
    }

    /**
     * بررسی وضعیت سلامت سرور
     */
    public function get_server_status() {
        return array(
            'php_version' => PHP_VERSION,
            'memory_usage' => $this->get_memory_usage(),
            'memory_limit' => $this->get_memory_limit(),
            'upload_max'   => ini_get('upload_max_filesize'),
            'post_max'     => ini_get('post_max_size')
        );
    }
}