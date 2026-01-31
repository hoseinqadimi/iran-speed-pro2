jQuery(document).ready(function($) {
    
    // مدیریت کلیک دکمه پاکسازی کش
    $('#isp-clear-cache-btn').on('click', function(e) {
        e.preventDefault();
        var $btn = $(this);
        
        if(!confirm('آیا از پاکسازی تمام کش‌ها اطمینان دارید؟')) return;

        $btn.text('در حال پاکسازی...').prop('disabled', true);

        $.ajax({
            url: isp_ajax_obj.ajax_url,
            type: 'POST',
            data: {
                action: 'isp_clear_cache',
                security: isp_ajax_obj.nonce
            },
            success: function(response) {
                if(response.success) {
                    alert(response.data);
                } else {
                    alert('خطا: ' + response.data);
                }
            },
            error: function() {
                alert('خطای سیستمی رخ داد.');
            },
            complete: function() {
                $btn.text('پاکسازی کش').prop('disabled', false);
            }
        });
    });

    // مدیریت کلیک دکمه بهینه‌سازی دیتابیس
    $('#isp-optimize-db-btn').on('click', function(e) {
        e.preventDefault();
        var $btn = $(this);

        if(!confirm('شروع عملیات جراحی دیتابیس؟ (توصیه می‌شود بک‌آپ داشته باشید)')) return;

        $btn.text('در حال جراحی...').prop('disabled', true);

        $.ajax({
            url: isp_ajax_obj.ajax_url,
            type: 'POST',
            data: {
                action: 'isp_optimize_db',
                security: isp_ajax_obj.nonce
            },
            success: function(response) {
                if(response.success) {
                    alert(response.data);
                    location.reload(); // رفرش برای نمایش آمار جدید
                } else {
                    alert('خطا: ' + response.data);
                }
            },
            complete: function() {
                $btn.text('بهینه‌سازی دیتابیس').prop('disabled', false);
            }
        });
    });
});