<?php
/**
 * رابط کاربری مدیریت و بهینه‌سازی رسانه‌ها
 * مسیر فایل: admin/views/media-table.php
 * نسخه نهایی و ۱۰۰٪ کامل
 */

if (!defined('ABSPATH')) {
    exit;
}

// مقداردهی کلاس‌های رسانه
$webp_converter = new ISP_Webp_Converter();
$image_cleaner  = new ISP_Image_Cleaner();

// دریافت لیست تصاویر بی‌استفاده
$unused_images = $image_cleaner->get_unused_media();
?>

<div class="isp-view-container">
    <div class="isp-view-header">
        <div class="header-icon">
            <span class="dashicons dashicons-format-image"></span>
        </div>
        <div class="header-text">
            <h2>بهینه‌سازی و جراحی رسانه‌ها</h2>
            <p>تبدیل خودکار تصاویر به فرمت WebP و حذف فایل‌هایی که در هیچ نوشته‌ای استفاده نشده‌اند.</p>
        </div>
    </div>

    <div class="isp-media-tools">
        <div class="isp-tool-card <?php echo $webp_converter->is_supported() ? 'status-active' : 'status-error'; ?>">
            <div class="tool-icon">
                <span class="dashicons dashicons-performance"></span>
            </div>
            <div class="tool-content">
                <h3>وضعیت مبدل WebP</h3>
                <p><?php echo $webp_converter->is_supported() ? 'کتابخانه GD/Imagick فعال است.' : 'خطا: سرور از تبدیل WebP پشتیبانی نمی‌کند.'; ?></p>
            </div>
            <div class="tool-action">
                <button type="button" class="button button-primary" <?php echo !$webp_converter->is_supported() ? 'disabled' : ''; ?>>
                    تبدیل دسته‌جمعی کل کتابخانه
                </button>
            </div>
        </div>
    </div>

    <div class="isp-media-list-section">
        <div class="list-header">
            <h3>تصاویر بدون استفاده (بار اضافی روی هاست)</h3>
            <span class="isp-badge badge-info"><?php echo count($unused_images); ?> فایل شناسایی شد</span>
        </div>

        <div class="isp-media-grid">
            <?php if (!empty($unused_images)) : ?>
                <?php foreach (array_slice($unused_images, 0, 12) as $image_id) : 
                    $thumb = wp_get_attachment_image_src($image_id, 'thumbnail');
                    $file_path = get_attached_file($image_id);
                    $file_size = file_exists($file_path) ? size_format(filesize($file_path)) : '0 KB';
                ?>
                    <div class="isp-media-item">
                        <div class="media-preview">
                            <img src="<?php echo esc_url($thumb[0]); ?>" alt="Preview">
                        </div>
                        <div class="media-details">
                            <span class="media-size"><?php echo esc_html($file_size); ?></span>
                            <label class="media-select">
                                <input type="checkbox" name="media_ids[]" value="<?php echo esc_attr($image_id); ?>">
                                انتخاب
                            </label>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else : ?>
                <div class="isp-empty-state">
                    <span class="dashicons dashicons-images-alt2"></span>
                    <p>تمام تصاویر شما در سایت استفاده شده‌اند. کتابخانه رسانه پاکیزه است!</p>
                </div>
            <?php endif; ?>
        </div>

        <?php if (!empty($unused_images)) : ?>
            <div class="isp-bulk-actions">
                <p class="description">حذف این تصاویر باعث آزاد شدن فضای هاست می‌شود و تاثیری در محتوای سایت ندارد.</p>
                <button type="button" class="button button-link-delete">حذف قطعی فایل‌های انتخاب شده</button>
            </div>
        <?php endif; ?>
    </div>
</div>

<style>
    .isp-media-tools { margin-bottom: 30px; }
    .isp-tool-card { background: #fff; padding: 25px; border-radius: 15px; border: 1px solid #e2e8f0; display: flex; align-items: center; gap: 20px; }
    .isp-tool-card.status-active { border-right: 6px solid #10b981; }
    .isp-tool-card.status-error { border-right: 6px solid #be123c; opacity: 0.8; }
    
    .tool-icon { background: #f1f5f9; padding: 15px; border-radius: 12px; }
    .tool-content { flex: 1; }
    .tool-content h3 { margin: 0 0 5px 0; }
    .tool-content p { margin: 0; color: #64748b; }

    .isp-media-list-section { background: #fff; padding: 25px; border-radius: 15px; border: 1px solid #e2e8f0; }
    .list-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
    
    .isp-media-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(130px, 1fr)); gap: 15px; margin-bottom: 20px; }
    .isp-media-item { border: 1px solid #f1f5f9; border-radius: 10px; overflow: hidden; background: #fafafa; }
    .media-preview img { width: 100%; height: 100px; object-fit: cover; }
    .media-details { padding: 8px; font-size: 11px; display: flex; justify-content: space-between; align-items: center; background: #fff; }
    .media-size { color: #94a3b8; font-weight: bold; }
    
    .isp-empty-state { grid-column: 1 / -1; text-align: center; padding: 40px; color: #94a3b8; }
    .isp-empty-state .dashicons { font-size: 50px; width: 50px; height: 50px; margin-bottom: 10px; }
    
    .isp-bulk-actions { border-top: 1px solid #f1f5f9; padding-top: 20px; display: flex; justify-content: space-between; align-items: center; }
</style>