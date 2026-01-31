<?php
/**
 * تنظیمات زره ایران (Network Settings)
 * نسخه ۱۰۰٪ کامل با استایل Glassmorphism
 */

if (!defined('ABSPATH')) exit;
$settings = get_option('isp_settings', array());
?>

<div class="isp-view-container">
    <div class="isp-view-header">
        <div class="header-icon"><span class="dashicons dashicons-shield"></span></div>
        <div class="header-text">
            <h2>زره ایران (امنیت و پایداری شبکه)</h2>
            <p>کنترل هوشمند API و ضربان قلبی وردپرس برای کاهش لود سرور.</p>
        </div>
    </div>

    <form method="post" action="options.php">
        <?php settings_fields('isp_settings_group'); ?>

        <div class="isp-settings-grid">
            <div class="isp-setting-card">
                <div class="card-header">
                    <span class="dashicons dashicons-lock"></span>
                    <h3>دیوار آتش REST API</h3>
                </div>
                <div class="card-content">
                    <p>مسدودسازی دسترسی‌های غیرمجاز ربات‌ها به متدهای حساس.</p>
                    <label class="isp-switch">
                        <input type="checkbox" name="isp_settings[api_firewall]" <?php checked($settings['api_firewall'] ?? '', 'on'); ?> value="on">
                        <span class="isp-slider"></span>
                    </label>
                </div>
            </div>

            <div class="isp-setting-card">
                <div class="card-header">
                    <span class="dashicons dashicons-heart"></span>
                    <h3>کنترل Heartbeat</h3>
                </div>
                <div class="card-content">
                    <p>کاهش فرکانس پالس وردپرس برای صرفه‌جویی در CPU.</p>
                    <select name="isp_settings[heartbeat_limit]" class="isp-select">
                        <option value="60" <?php selected($settings['heartbeat_limit'] ?? '', '60'); ?>>هر ۶۰ ثانیه</option>
                        <option value="120" <?php selected($settings['heartbeat_limit'] ?? '', '120'); ?>>هر ۱۲۰ ثانیه</option>
                        <option value="disable" <?php selected($settings['heartbeat_limit'] ?? '', 'disable'); ?>>غیرفعال‌سازی</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="isp-footer-actions">
            <button type="submit" class="button button-primary isp-btn-save">ذخیره تنظیمات شبکه</button>
        </div>
    </form>
</div>