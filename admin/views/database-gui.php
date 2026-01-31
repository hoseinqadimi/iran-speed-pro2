<?php
/**
 * رابط کاربری جراحی دیتابیس
 * نسخه نهایی و ۱۰۰٪ عملیاتی
 */

if (!defined('ABSPATH')) exit;

$detector = new ISP_Orphan_Detector();
$optimizer = new ISP_DB_Optimizer();
$orphan_tables = $detector->get_orphan_tables();
?>

<div class="isp-view-container">
    <div class="isp-view-header">
        <div class="header-icon"><span class="dashicons dashicons-database-remove"></span></div>
        <div class="header-text">
            <h2>جراحی دیتابیس (پاکسازی جداول یتیم)</h2>
            <p>جداول زیر متعلق به افزونه‌های حذف شده هستند و فضای دیتابیس را اشغال کرده‌اند.</p>
        </div>
    </div>

    <div class="isp-action-bar">
        <button type="button" id="isp-run-backup" class="button button-primary">
            <span class="dashicons dashicons-backup"></span> تهیه بک‌آپ سریع (JSON)
        </button>
        <button type="button" id="isp-run-optimize" class="button button-secondary">
            <span class="dashicons dashicons-admin-generic"></span> بهینه‌سازی ساختار (Optimize)
        </button>
    </div>

    <div class="isp-table-wrapper">
        <table class="wp-list-table widefat fixed striped">
            <thead>
                <tr>
                    <td id="cb" class="manage-column column-cb check-column"><input id="isp-select-all" type="checkbox"></td>
                    <th>نام جدول</th>
                    <th>حجم</th>
                    <th>وضعیت</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($orphan_tables)) : foreach ($orphan_tables as $table) : ?>
                    <tr>
                        <th class="check-column"><input type="checkbox" name="tables[]" value="<?php echo esc_attr($table); ?>"></th>
                        <td><code><?php echo esc_html($table); ?></code></td>
                        <td><?php echo esc_html($optimizer->get_table_size($table)); ?></td>
                        <td><span class="isp-badge warning">بدون استفاده</span></td>
                    </tr>
                <?php endforeach; else : ?>
                    <tr><td colspan="4" style="text-align:center; padding:20px;">دیتابیس شما کاملاً پاکیزه است.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>