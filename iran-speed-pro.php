<?php
/**
 * Plugin Name:       Iran Speed Pro
 * Plugin URI:        https://example.com/iran-speed-pro
 * Description:       سوپر افزونه بهینه‌سازی وردپرس با رویکرد اینترنت ایران، مانیتورینگ هوشمند و جراحی دیتابیس.
 * Version:           1.0.0
 * Author:            Your Name
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       iran-speed-pro
 * Domain Path:       /languages
 */

// ۱. جلوگیری از دسترسی مستقیم برای امنیت
if (!defined('ABSPATH')) {
    exit;
}

// ۲. تعریف ثابت‌های حیاتی مسیردهی
define('ISP_VERSION', '1.0.0');
define('ISP_PATH', plugin_dir_path(__FILE__));
define('ISP_URL', plugin_dir_url(__FILE__));

// ۳. فراخوانی موتور بارگذاری خودکار (Autoloader)
require_once ISP_PATH . 'core/class-autoloader.php';

// ثبت اتولودر
$autoloader = new ISP_Autoloader();
$autoloader->register();

/**
 * ۴. مدیریت فعال‌سازی افزونه
 */
function activate_iran_speed_pro() {
    ISP_Activator::activate();
}
register_activation_hook(__FILE__, 'activate_iran_speed_pro');

/**
 * ۵. مدیریت غیرفعال‌سازی افزونه
 */
function deactivate_iran_speed_pro() {
    ISP_Deactivator::deactivate();
}
register_deactivation_hook(__FILE__, 'deactivate_iran_speed_pro');

/**
 * ۶. راه‌اندازی زبان و هسته اصلی
 */
function run_iran_speed_pro() {
    // بارگذاری ترجمه‌ها
    $i18n = new ISP_I18n();
    add_action('plugins_loaded', array($i18n, 'load_plugin_textdomain'));

    // اجرای مغز متفکر افزونه
    $plugin = ISP_Bootstrap::get_instance();
    $plugin->init();

    // اجرای بخش مدیریت
    if (is_admin()) {
        $admin = new ISP_Admin();
        $admin->init();
    }
}

// استارت نهایی
run_iran_speed_pro();