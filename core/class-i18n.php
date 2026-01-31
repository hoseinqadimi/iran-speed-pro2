<?php
/**
 * کلاس بین‌المللی‌سازی (Internationalization)
 * وظیفه: بارگذاری فایل‌های ترجمه افزونه.
 */

// جلوگیری از دسترسی مستقیم
if (!defined('ABSPATH')) {
    exit;
}

class ISP_I18n {

    /**
     * بارگذاری دامنه متنی افزونه برای ترجمه
     */
    public function load_plugin_textdomain() {
        load_plugin_textdomain(
            'iran-speed-pro',
            false,
            dirname(dirname(plugin_basename(__FILE__))) . '/languages/'
        );
    }
}