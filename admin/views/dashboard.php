<?php
/**
 * Iran Speed Pro 2 - Dashboard HTML View
 * نمایش آمارهای واقعی و دکمه‌های عملیاتی
 * مسیر: admin/views/dashboard.php
 */

if (!defined('ABSPATH')) {
    exit;
}
?>

<div class="isp-dashboard-wrapper">
    <div class="isp-header-section">
        <h1>داشبورد ایران اسپید پرو <span class="version-tag">2.0.0</span></h1>
        <p class="description">بهینه‌سازی هوشمند سرعت و امنیت برای سایت‌های وردپرسی</p>
    </div>

    <hr>

    <div class="isp-main-grid">
        
        <div class="isp-card">
            <div class="card-header">
                <span class="dashicons dashicons-database"></span>
                <h2>بهینه‌سازی دیتابیس</h2>
            </div>
            <div class="card-body">
                <p>حجم کنونی دیتابیس: <strong><?php echo esc_html($db_stats['total_size'] ?? 'در حال محاسبه...'); ?></strong></p>
                <p>تعداد جداول بهینه‌نشده: <strong><?php echo esc_html($db_stats['overhead_count'] ?? '0'); ?></strong></p>
                <button id="isp-optimize-db-btn" class="button button-primary button-large">شروع جراحی دیتابیس</button>
            </div>
        </div>

        <div class="isp-card">
            <div class="card-header">
                <span class="dashicons dashicons-performance"></span>
                <h2>مدیریت حافظه کش</h2>
            </div>
            <div class="card-body">
                <p>پاکسازی کش‌های لایت‌اسپید، فایل‌ها و Object Cache وردپرس به صورت یکپارچه.</p>
                <br>
                <button id="isp-clear-cache-btn" class="button button-secondary button-large">تخلیه کامل کش سایت</button>
            </div>
        </div>

    </div>

    <div class="isp-log-container">
        <h3>آخرین گزارشات فعالیت سیستم</h3>
        <div class="log-box">
            <?php 
            $logs = ISP_Logger::get_logs(5);
            if (!empty($logs)) {
                foreach ($logs as $log) {
                    echo '<div class="log-item">' . esc_html($log) . '</div>';
                }
            } else {
                echo '<p>هیچ گزارشی ثبت نشده است.</p>';
            }
            ?>
        </div>
    </div>
</div>

<style>
    /* استایل سریع برای نمایش درست داشبورد */
    .isp-dashboard-wrapper { margin-top: 20px; padding-right: 20px; direction: rtl; font-family: Tahoma, sans-serif; }
    .isp-main-grid { display: flex; gap: 20px; margin-top: 20px; }
    .isp-card { background: #fff; border: 1px solid #ccd0d4; padding: 20px; flex: 1; border-radius: 4px; box-shadow: 0 1px 3px rgba(0,0,0,0.04); }
    .card-header h2 { display: inline-block; margin: 0 10px; font-size: 16px; }
    .isp-log-container { margin-top: 30px; background: #23282d; color: #00ff00; padding: 15px; border-radius: 4px; }
    .log-box { font-family: monospace; font-size: 13px; line-height: 1.6; }
    .version-tag { background: #0073aa; color: #fff; font-size: 10px; padding: 2px 6px; border-radius: 10px; vertical-align: middle; }
</style>