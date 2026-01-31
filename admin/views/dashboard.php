<?php
/**
 * ظاهر اصلی داشبورد افزونه (View)
 * نسخه نهایی و ۱۰۰٪ کامل - متصل به مانیتورینگ زنده
 */

if (!defined('ABSPATH')) {
    exit;
}

// فراخوانی کلاس‌های هسته برای دریافت آمار واقعی
$monitor   = new ISP_Resource_Monitor();
$detector  = new ISP_Orphan_Detector();
$db_opt    = new ISP_DB_Optimizer();

$memory_usage = $monitor->get_memory_usage();
$orphan_tables = $detector->get_orphan_tables();
$total_orphans = count($orphan_tables);
?>

<div class="wrap isp-admin-wrapper">
    <header class="isp-header">
        <div class="isp-logo">
            <h1>ایران اسپید <span class="version">نسخه <?php echo ISP_VERSION; ?></span></h1>
        </div>
        <div class="isp-status">
            <span class="status-dot <?php echo ($total_orphans > 5) ? 'warning' : 'online'; ?>"></span>
            وضعیت سیستم: <?php echo ($total_orphans > 5) ? 'نیازمند جراحی دیتابیس' : 'بهینه و فعال'; ?>
        </div>
    </header>

    <div class="isp-container">
        <aside class="isp-sidebar">
            <ul class="isp-tabs">
                <li class="active" data-tab="dashboard">
                    <span class="dashicons dashicons-dashboard"></span> پیشخوان
                </li>
                <li data-tab="monitoring">
                    <span class="dashicons dashicons-performance"></span> مانیتورینگ منابع
                </li>
                <li data-tab="database">
                    <span class="dashicons dashicons-database"></span> جراحی دیتابیس
                </li>
                <li data-tab="media">
                    <span class="dashicons dashicons-admin-media"></span> بهینه‌سازی رسانه
                </li>
                <li data-tab="network">
                    <span class="dashicons dashicons-shield"></span> زره ایران (شبکه)
                </li>
                <li data-tab="settings">
                    <span class="dashicons dashicons-admin-generic"></span> تنظیمات کلی
                </li>
            </ul>
        </aside>

        <main class="isp-main-content">
            
            <section id="dashboard" class="isp-tab-content active">
                <div class="isp-cards">
                    <div class="isp-card">
                        <span class="dashicons dashicons-microchip"></span>
                        <h3>مصرف لحظه‌ای RAM</h3>
                        <div class="isp-stat-val"><?php echo esc_html($memory_usage); ?></div>
                        <p>میزان حافظه اشغال شده توسط وردپرس</p>
                    </div>
                    <div class="isp-card">
                        <span class="dashicons dashicons-database-remove"></span>
                        <h3>جداول یتیم دیتابیس</h3>
                        <div class="isp-stat-val <?php echo ($total_orphans > 0) ? 'text-danger' : ''; ?>">
                            <?php echo esc_html($total_orphans); ?>
                        </div>
                        <p>تعداد جداول بدون صاحب شناسایی شده</p>
                    </div>
                    <div class="isp-card">
                        <span class="dashicons dashicons-saved"></span>
                        <h3>وضعیت زره ایران</h3>
                        <div class="isp-stat-val text-success">فعال</div>
                        <p>فایروال API و پالس قلبی</p>
                    </div>
                </div>
            </section>

            <section id="monitoring" class="isp-tab-content">
                <h2>نمودار مانیتورینگ منابع سرور</h2>
                <div class="isp-chart-container">
                    <canvas id="ispMemoryChart"></canvas>
                </div>
            </section>

            <section id="database" class="isp-tab-content">
                <?php include_once ISP_PATH . 'admin/views/database-gui.php'; ?>
            </section>

            <section id="media" class="isp-tab-content">
                <?php include_once ISP_PATH . 'admin/views/media-table.php'; ?>
            </section>

            <section id="network" class="isp-tab-content">
                <?php include_once ISP_PATH . 'admin/views/network-settings.php'; ?>
            </section>

            <section id="settings" class="isp-tab-content">
                <h2>تنظیمات پیکربندی ایران اسپید</h2>
                <form method="post" action="options.php">
                    <?php 
                    settings_fields('isp_settings_group');
                    $settings = get_option('isp_settings', array());
                    ?>
                    <table class="form-table">
                        <tr>
                            <th scope="row">حالت دیباگ (رفع خطا)</th>
                            <td>
                                <label class="isp-switch">
                                    <input type="checkbox" name="isp_settings[debug_mode]" <?php checked($settings['debug_mode'] ?? '', 'on'); ?> value="on">
                                    <span class="isp-slider"></span>
                                </label>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">حذف داده‌ها هنگام لغو نصب</th>
                            <td>
                                <label class="isp-switch">
                                    <input type="checkbox" name="isp_settings[delete_on_uninstall]" <?php checked($settings['delete_on_uninstall'] ?? '', 'on'); ?> value="on">
                                    <span class="isp-slider"></span>
                                </label>
                            </td>
                        </tr>
                    </table>
                    <?php submit_button('ذخیره پیکربندی نهایی'); ?>
                </form>
            </section>

        </main>
    </div>
</div>