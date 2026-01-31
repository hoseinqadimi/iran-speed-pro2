<?php
/**
 * Iran Speed Pro 2 - Dashboard View
 * مسیر: admin/views/dashboard.php
 */

if (!defined('ABSPATH')) {
    exit;
}
?>

<div class="isp-dashboard-container">
    <div class="isp-header">
        <div class="isp-logo">
            <h1>ایران اسپید پرو <span class="badge">v2.0</span></h1>
        </div>
        <div class="isp-quick-stats">
            <span class="status-indicator online">سیستم آماده است</span>
        </div>
    </div>

    <div class="isp-main-grid">
        <div class="isp-card">
            <div class="card-icon"><span class="dashicons dashicons-database"></span></div>
            <h2>بهینه‌سازی دیتابیس</h2>
            <div class="card-content">
                <ul class="stats-list">
                    <li>حجم دیتابیس: <strong><?php echo esc_html($db_stats['total_size'] ?? '0 MB'); ?></strong></li>
                    <li>جداول سربار (Overhead): <strong><?php echo esc_html($db_stats['overhead_count'] ?? '0'); ?></strong></li>
                </ul>
                <button id="isp-optimize-db-btn" class="isp-btn btn-primary">شروع جراحی دیتابیس</button>
            </div>
        </div>

        <div class="isp-card">
            <div class="card-icon"><span class="dashicons dashicons-performance"></span></div>
            <h2>مدیریت حافظه کش</h2>
            <div class="card-content">
                <p>تخلیه کامل کش لایت‌اسپید، آبجکت‌کش و کش‌های داخلی وردپرس.</p>
                <button id="isp-clear-cache-btn" class="isp-btn btn-secondary">تخلیه سراسری کش</button>
            </div>
        </div>
    </div>

    <div class="isp-logger-section">
        <h3>آخرین گزارشات فعالیت</h3>
        <div class="log-window">
            <?php 
            $logs = ISP_Logger::get_logs(5);
            if (!empty($logs)) {
                foreach ($logs as $log) {
                    echo '<p class="log-entry">' . esc_html($log) . '</p>';
                }
            } else {
                echo '<p>گزارشی یافت نشد.</p>';
            }
            ?>
        </div>
    </div>
</div>