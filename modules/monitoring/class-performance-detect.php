<?php
/**
 * تشخیص دهنده عملکرد (Performance Detector)
 * وظیفه: آنالیز سرعت اجرای کوئری‌ها و زمان لود صفحه.
 */

if (!defined('ABSPATH')) exit;

class ISP_Performance_Detect {

    /**
     * ثبت زمان شروع اجرای وردپرس
     */
    private $execution_start;

    public function __construct() {
        $this->execution_start = microtime(true);
    }

    /**
     * محاسبه زمان نهایی لود فرانت‌ند
     */
    public function get_load_time() {
        $end_time = microtime(true);
        return round($end_time - $this->execution_start, 4);
    }

    /**
     * بررسی تعداد کوئری‌های زده شده به دیتابیس
     * این بخش به بهینه‌سازی دیتابیس کمک شایانی می‌کند.
     */
    public function get_query_count() {
        global $wpdb;
        return $wpdb->num_queries;
    }

    /**
     * تشخیص سنگین بودن سایت بر اساس استانداردهای ایران اسپید
     */
    public function get_performance_grade() {
        $time = $this->get_load_time();
        if ($time < 0.5) return 'A+';
        if ($time < 1.0) return 'B';
        if ($time < 2.0) return 'C';
        return 'D (نیاز به جراحی فوری)';
    }
}