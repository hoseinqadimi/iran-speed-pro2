<?php
/**
 * پیش‌بارگذار هوشمند منابع (Advanced Preloader)
 * وظیفه: اولویت‌بندی بارگذاری فونت‌ها و اتصالات شبکه برای سرعت رندر بالاتر.
 */

if (!defined('ABSPATH')) exit;

class ISP_Preloader {

    /**
     * اجرای هوک‌های مربوط به هدر سایت
     */
    public function init() {
        if (is_admin()) return;
        
        // اولویت ۱ برای چاپ سریع‌تر در بالاترین قسمت تگ head
        add_action('wp_head', array($this, 'render_preload_tags'), 1);
        add_action('wp_head', array($this, 'render_dns_prefetch'), 2);
    }

    /**
     * پیش‌بارگذاری فونت‌های اصلی و استایل‌های حیاتی
     */
    public function render_preload_tags() {
        // شناسایی مسیر فونت‌های قالب (مثلاً ایران‌سنس)
        // نکته: در نسخه نهایی، این مسیرها از تنظیمات پنل مدیریت خوانده می‌شوند
        $fonts = array(
            '/assets/fonts/iransans-bold.woff2',
            '/assets/fonts/iransans-light.woff2'
        );

        foreach ($fonts as $font_path) {
            $full_url = get_stylesheet_directory_uri() . $font_path;
            echo '<link rel="preload" href="' . esc_url($full_url) . '" as="font" type="font/woff2" crossorigin>' . "\n";
        }
    }

    /**
     * اتصال پیش‌دستانه به سرویس‌های خارجی (DNS Prefetch)
     * این کار زمان لود سرویس‌هایی مثل آمارگیر یا فونت گوگل را کاهش می‌دهد.
     */
    public function render_dns_prefetch() {
        $domains = array(
            'fonts.googleapis.com',
            'www.google-analytics.com',
            'googletagmanager.com'
        );

        foreach ($domains as $domain) {
            echo '<link rel="dns-prefetch" href="//' . esc_attr($domain) . '">' . "\n";
        }
    }
}