/**
 * مسیر: admin/js/iran-speed-pro-admin.js
 */
jQuery(document).ready(function($) {
    // دکمه پاکسازی کش
    $('#isp-clear-cache-btn').on('click', function(e) {
        e.preventDefault();
        var $btn = $(this);
        if(!confirm('کش پاکسازی شود؟')) return;
        $btn.prop('disabled', true).text('در حال انجام...');
        $.post(isp_ajax_obj.ajax_url, {
            action: 'isp_clear_cache',
            security: isp_ajax_obj.nonce
        }, function(res) {
            alert(res.data);
            $btn.prop('disabled', false).text('تخلیه کامل کش سایت');
        });
    });

    // دکمه بهینه‌سازی دیتابیس
    $('#isp-optimize-db-btn').on('click', function(e) {
        e.preventDefault();
        var $btn = $(this);
        if(!confirm('جراحی دیتابیس آغاز شود؟')) return;
        $btn.prop('disabled', true).text('در حال جراحی...');
        $.post(isp_ajax_obj.ajax_url, {
            action: 'isp_optimize_db',
            security: isp_ajax_obj.nonce
        }, function(res) {
            alert(res.data);
            location.reload();
        });
    });
});