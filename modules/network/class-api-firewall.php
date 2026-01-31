<?php
/**
 * دیوار آتش API (API Firewall)
 * وظیفه: مسدودسازی درخواست‌های خارجی کند و ایمن‌سازی REST API.
 */

if (!defined('ABSPATH')) exit;

class ISP_Network_Api_Firewall {

    /**
     * اجرای فیلترهای حفاظتی شبکه
     */
    public function init() {
        // ۱. غیرفعال کردن آواتارهای خارجی (Gravatar) برای سرعت بیشتر در بخش کامنت‌ها
        add_filter('get_option_show_avatars', '__return_false');

        // ۲. غیرفعال کردن لینک‌های REST API در هدر سایت برای امنیت و کاهش حجم
        remove_action('wp_head', 'rest_output_link_wp_head');
        remove_action('wp_head', 'wp_oembed_add_discovery_links');

        // ۳. جلوگیری از دسترسی کاربران وارد نشده به لیست کاربران از طریق API
        add_filter('rest_authentication_errors', array($this, 'restrict_rest_api'));
    }

    /**
     * محدود کردن دسترسی به REST API برای امنیت بیشتر
     */
    public function restrict_rest_api($result) {
        if (!empty($result)) {
            return $result;
        }

        if (!is_user_logged_in()) {
            return new WP_Error('rest_not_logged_in', 'دسترسی محدود شده است.', array('status' => 401));
        }

        return $result;
    }
}