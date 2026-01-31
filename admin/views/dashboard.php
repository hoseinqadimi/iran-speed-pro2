<?php
/**
 * Iran Speed Pro 2 - Dashboard UI
 * این فایل مسئول نمایش رابط کاربری افزونه در پیشخوان وردپرس است.
 * * @package    Iran_Speed_Pro
 * @subpackage Iran_Speed_Pro/admin/views
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // امنیت
}
?>

<div class="wrap isp-dashboard-wrapper">
    <div class="isp-header">
        <div class="isp-brand">
            <span class="dashicons dashicons-performance"></span>
            <h1>ایران اسپید پرو <small>نسخه 2.0.0</small></h1>
        </div>
        <div class="isp-server-status">
            <span class="status-dot"></span>
            وضعیت سرور: <strong>بهینه</strong>
        </div>
    </div>

    <div class="isp-grid">
        
        <div class="isp-card isp-card-db">
            <div class="card-icon"><span class="dashicons dashicons-database"></span></div>
            <div class="card-content">
                <h3>وضعیت دیتابیس</h3>
                <div class="stat-row">
                    <span>حجم کل دیتابیس:</span>
                    <strong><?php echo esc_html( $db_stats['total_size'] ?? 'نامشخص' ); ?></strong>
                </div>
                <div class="stat-row">
                    <span>جداول دارای سربار:</span>
                    <span class="badge <?php echo ($db_stats['overhead_count'] > 0) ? 'warning' : 'success'; ?>">
                        <?php echo esc_html( $db_stats['overhead_count'] ?? '0' ); ?> جدول
                    </span>
                </div>
                <button id="isp-optimize-db-btn" class="button button-primary">
                    <span class="dashicons dashicons-admin-tools"></span> شروع جراحی دیتابیس
                </button>
            </div>
        </div>

        <div class="isp-card isp-card-cache">
            <div class="card-icon"><span class="dashicons dashicons-plugins-checked"></span></div>
            <div class="card-content">
                <h3>مدیریت کش (Cache)</h3>
                <p>تخلیه همزمان کش لایت‌اسپید، Object Cache و فایل‌های استاتیک.</p>
                <div class="cache-status">
                    <span class="dashicons dashicons-yes-alt"></span> سیستم آماده پاکسازی
                </div>
                <button id="isp-clear-cache-btn" class="button button-secondary">
                    <span class="dashicons dashicons-trash"></span> تخلیه کامل کش سایت
                </button>
            </div>
        </div>

    </div>

    <div class="isp-card isp-log-viewer">
        <div class="card-header">
            <h3><span class="dashicons dashicons-list-view"></span> گزارش فعالیت‌های اخیر</h3>
            <span class="log-count">نمایش ۵ مورد آخر</span>
        </div>
        <div class="log-body" id="isp-log-window">
            <?php 
            $logs = ISP_Logger::get_logs( 5 ); 
            if ( ! empty( $logs ) ) :
                foreach ( $logs as $log ) : ?>
                    <div class="log-entry">
                        <span class="log-time">[<?php echo date('H:i:s'); ?>]</span>
                        <span class="log-msg"><?php echo esc_html( $log ); ?></span>
                    </div>
                <?php endforeach;
            else : ?>
                <p class="no-logs">هنوز هیچ گزارشی ثبت نشده است.</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<style>
    /* استایل‌های اختصاصی داشبورد */
    .isp-dashboard-wrapper { margin: 20px 20px 0 0; font-family: 'Tahoma', sans-serif; direction: rtl; }
    .isp-header { display: flex; justify-content: space-between; align-items: center; background: #fff; padding: 15px 25px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); margin-bottom: 25px; }
    .isp-brand h1 { margin: 0; font-size: 22px; color: #1d2327; }
    .isp-brand h1 small { font-size: 12px; background: #0073aa; color: #fff; padding: 2px 8px; border-radius: 10px; margin-right: 10px; }
    
    .isp-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px; margin-bottom: 25px; }
    .isp-card { background: #fff; border-radius: 8px; padding: 25px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); position: relative; overflow: hidden; }
    
    .card-icon { position: absolute; left: -10px; top: -10px; opacity: 0.05; }
    .card-icon .dashicons { font-size: 100px; width: 100px; height: 100px; }
    
    .stat-row { display: flex; justify-content: space-between; margin: 15px 0; padding-bottom: 10px; border-bottom: 1px dashed #eee; }
    .badge { padding: 2px 10px; border-radius: 4px; font-size: 12px; }
    .badge.warning { background: #fff8e5; color: #856404; border: 1px solid #ffeeba; }
    .badge.success { background: #e7ffec; color: #1e7336; border: 1px solid #c3e6cb; }

    .isp-log-viewer { background: #1d2327; color: #dcdcde; border: none; }
    .isp-log-viewer h3 { color: #fff; margin: 0; }
    .log-body { background: #2c3338; padding: 15px; border-radius: 4px; margin-top: 15px; max-height: 200px; overflow-y: auto; }
    .log-entry { font-family: monospace; padding: 5px 0; border-bottom: 1px solid #3c434a; font-size: 13px; }
    .log-time { color: #72aee6; margin-left: 10px; }
    
    /* افکت پالس برای وضعیت سرور */
    .status-dot { height: 10px; width: 10px; background-color: #46b450; border-radius: 50%; display: inline-block; margin-left: 5px; box-shadow: 0 0 0 rgba(70, 180, 80, 0.4); animation: pulse 2s infinite; }
    @keyframes pulse { 0% { box-shadow: 0 0 0 0 rgba(70, 180, 80, 0.7); } 70% { box-shadow: 0 0 0 10px rgba(70, 180, 80, 0); } 100% { box-shadow: 0 0 0 0 rgba(70, 180, 80, 0); } }
    
    .button-large { padding: 10px 20px !important; height: auto !important; }
</style>