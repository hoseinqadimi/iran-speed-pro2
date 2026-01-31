/**
 * Iran Speed Pro 2 - Admin AJAX Handler
 * این فایل مسئول مدیریت کلیک دکمه‌ها و ارسال درخواست‌های AJAX به سرور با رعایت توکن امنیتی است.
 * * @package    Iran_Speed_Pro
 * @author     Hosein Qadimi
 */

(function( $ ) {
    'use strict';

    /**
     * اجرای کدها پس از بارگذاری کامل صفحه
     */
    $(function() {

        /**
         * ۱. بخش عملیات بهینه‌سازی دیتابیس
         */
        const $optimizeDbBtn = $('#isp-optimize-db-btn');
        
        $optimizeDbBtn.on('click', function(e) {
            e.preventDefault();

            // تاییدیه گرفتن از کاربر قبل از شروع جراحی دیتابیس
            if ( ! confirm( 'هشدار: جراحی دیتابیس شامل بهینه‌سازی جداول و حذف داده‌های اضافی است. آیا مطمئن هستید؟' ) ) {
                return;
            }

            const $btn = $(this);
            const originalText = $btn.text();

            // غیرفعال کردن دکمه و نمایش وضعیت در حال اجرا
            $btn.prop('disabled', true)
                .addClass('updating-message')
                .text('در حال جراحی و بهینه‌سازی دیتابیس...');

            // ارسال درخواست AJAX به وردپرس
            $.ajax({
                url: isp_ajax_obj.ajax_url,
                type: 'POST',
                dataType: 'json',
                data: {
                    action: 'isp_process_db_optimization', // هماهنگ با کلاس ISP_Admin
                    security: isp_ajax_obj.nonce          // توکن امنیتی Nonce
                },
                success: function(response) {
                    if ( response.success ) {
                        alert( 'موفقیت: ' + response.data );
                        // بارگذاری مجدد صفحه برای بروزرسانی آمار حجم دیتابیس در پنل
                        location.reload();
                    } else {
                        alert( 'خطا: ' + response.data );
                    }
                },
                error: function(xhr, status, error) {
                    console.error('ISP Error:', error);
                    alert( 'خطایی در ارتباط با سرور رخ داد. لطفا دوباره تلاش کنید.' );
                },
                complete: function() {
                    // بازگرداندن دکمه به حالت اولیه در صورت عدم رفرش صفحه
                    $btn.prop('disabled', false)
                        .removeClass('updating-message')
                        .text(originalText);
                }
            });
        });

        /**
         * ۲. بخش عملیات پاکسازی سراسری کش
         */
        const $clearCacheBtn = $('#isp-clear-cache-btn');

        $clearCacheBtn.on('click', function(e) {
            e.preventDefault();

            const $btn = $(this);
            const originalText = $btn.text();

            // شروع فرآیند بصری لودینگ
            $btn.prop('disabled', true)
                .addClass('updating-message')
                .text('در حال تخلیه تمامی لایه‌های کش...');

            $.ajax({
                url: isp_ajax_obj.ajax_url,
                type: 'POST',
                dataType: 'json',
                data: {
                    action: 'isp_process_clear_cache', // هماهنگ با کلاس ISP_Admin
                    security: isp_ajax_obj.nonce       // توکن امنیتی Nonce
                },
                success: function(response) {
                    if ( response.success ) {
                        alert( response.data );
                    } else {
                        alert( 'خطا در پاکسازی: ' + response.data );
                    }
                },
                error: function() {
                    alert( 'ارتباط با سرور برای پاکسازی کش برقرار نشد.' );
                },
                complete: function() {
                    $btn.prop('disabled', false)
                        .removeClass('updating-message')
                        .text(originalText);
                }
            });
        });

        /**
         * ۳. سیستم نمایش پیام‌های لاگ به صورت انیمیشنی (اختیاری)
         */
        const $logWindow = $('#isp-log-window');
        if ( $logWindow.length ) {
            $logWindow.scrollTop($logWindow[0].scrollHeight);
        }

    });

})( jQuery );