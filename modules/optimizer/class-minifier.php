<?php
/**
 * فشرده‌ساز جامع خروجی (Full Minifier)
 * وظیفه: پاکسازی نهایی کدها بدون آسیب به منطق اسکریپت‌ها.
 */

if (!defined('ABSPATH')) exit;

class ISP_Minifier {

    public function init() {
        if (!is_admin() && !isset($_GET['preview'])) {
            add_action('template_redirect', array($this, 'start_ob'), 20);
        }
    }

    public function start_ob() {
        ob_start(array($this, 'process_output'));
    }

    public function process_output($buffer) {
        if ($this->is_html($buffer)) {
            return $this->minify_html($buffer);
        }
        return $buffer;
    }

    private function is_html($buffer) {
        return (strpos($buffer, '<html') !== false);
    }

    private function minify_html($html) {
        $search = array(
            '/(\n|^)(\x20+|\t)/', // حذف فاصله‌های ابتدای خط
            '/(\n|^)\/\/(.*?)(\n|$)/', // حذف کامنت‌های تک خطی JS (با احتیاط)
            '/\n/', // حذف کاراکترهای خط جدید
            '/\/\*.*?\*\//s', // حذف کامنت‌های چند خطی CSS/JS
            '/\s+/' // تبدیل فضاهای خالی اضافی به یک فضا
        );

        $replace = array("\n", "\n", " ", "", " ");
        
        // جلوگیری از فشرده‌سازی تگ‌های <pre> و <textarea> برای حفظ فرمت کاربر
        if (preg_match_all('/<(pre|textarea|code)[^>]*>.*?<\/\\1>/si', $html, $matches)) {
            foreach ($matches[0] as $i => $match) {
                $html = str_replace($match, "###ISP_PRE_PLACEHOLDER_{$i}###", $html);
            }
        }

        $html = preg_replace($search, $replace, $html);

        // بازگرداندن تگ‌های محافظت شده
        if (isset($matches[0])) {
            foreach ($matches[0] as $i => $match) {
                $html = str_replace("###ISP_PRE_PLACEHOLDER_{$i}###", $match, $html);
            }
        }

        return trim($html);
    }
}