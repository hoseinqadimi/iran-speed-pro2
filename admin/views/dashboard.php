<?php
/**
 * پیشخوان اصلی ایران اسپید پرو
 * مسیر: admin/views/dashboard.php
 */
if (!defined('ABSPATH')) exit;

// مقداردهی کلاس‌های عملیاتی برای نمایش آمار واقعی
$monitor  = new ISP_Resource_Monitor();
$detector = new ISP_Orphan_Detector();

$mem_usage     = $monitor->get_memory_usage();
$orphan_tables = $detector->get_orphan_tables();
$total_orphans = count($orphan_tables);
?>

<div class="wrap isp-admin-wrapper">
    <header class="isp-header">
        <div class="isp-logo">
            <h1>ایران اسپید <span class="version">نسخه <?php echo ISP_VERSION; ?></span></h1>
        </div>
        <div class="isp-status">
            <span class="status-dot <?php echo ($total_orphans > 0) ? 'warning' : 'online'; ?>"></span>
            وضعیت: <?php echo ($total_orphans > 0) ? 'نیاز به بهینه‌سازی' : 'سیستم پایدار'; ?>
        </div>
    </header>

    <div class="isp-container">
        <aside class="isp-sidebar">
            <ul class="isp-tabs">
                <li class="active" data-tab="dashboard"><span class="dashicons dashicons-dashboard"></span> پیشخوان</li>
                <li data-tab="monitoring"><span class="dashicons dashicons-performance"></span> مانیتورینگ</li>
                <li data-tab="database"><span class="dashicons dashicons-database"></span> جراحی دیتابیس</li>
                <li data-tab="media"><span class="dashicons dashicons-admin-media"></span> رسانه</li>
                <li data-tab="network"><span class="dashicons dashicons-shield"></span> زره ایران</li>
                <li data-tab="settings"><span class="dashicons dashicons-admin-generic"></span> تنظیمات</li>
            </ul>
        </aside>

        <main class="isp-main-content">
            <section id="dashboard" class="isp-tab-content active">
                <div class="isp-cards">
                    <div class="isp-card">
                        <span class="dashicons dashicons-microchip"></span>
                        <h3>مصرف حافظه RAM</h3>
                        <div class="isp-stat-val"><?php echo esc_html($mem_usage); ?></div>
                        <p>توسط وردپرس در این لحظه</p>
                    </div>
                    <div class="isp-card">
                        <span class="dashicons dashicons-database-remove"></span>
                        <h3>جداول اضافی</h3>
                        <div class="isp-stat-val <?php echo ($total_orphans > 0) ? 'text-danger' : ''; ?>">
                            <?php echo esc_html($total_orphans); ?>
                        </div>
                        <p>جداول یتیم شناسایی شده</p>
                    </div>
                </div>
            </section>

            <section id="monitoring" class="isp-tab-content">
                <canvas id="ispMemoryChart" width="400" height="200"></canvas>
            </section>
            
            <section id="database" class="isp-tab-content">
                <?php include ISP_PATH . 'admin/views/database-gui.php'; ?>
            </section>

            <section id="network" class="isp-tab-content">
                <?php include ISP_PATH . 'admin/views/network-settings.php'; ?>
            </section>

            <section id="settings" class="isp-tab-content">
                <form method="post" action="options.php">
                    <?php settings_fields('isp_settings_group'); $s = get_option('isp_settings'); ?>
                    <table class="form-table">
                        <tr>
                            <th>حالت رفع خطا (Debug)</th>
                            <td><input type="checkbox" name="isp_settings[debug]" <?php checked($s['debug'] ?? '', 'on'); ?> value="on"></td>
                        </tr>
                    </table>
                    <?php submit_button('ذخیره تغییرات نهایی'); ?>
                </form>
            </section>
        </main>
    </div>
</div>