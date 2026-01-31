/**
 * راه‌اندازی نمودارهای گرافیکی مانیتورینگ
 * مسیر: admin/js/charts-init.js
 */

document.addEventListener('DOMContentLoaded', function() {
    
    // ۱. تنظیمات اولیه نمودار دایره‌ای مصرف رم
    const ctx = document.getElementById('ispMemoryChart');
    
    if (ctx) {
        // دریافت مقدار عددی از المان HTML که توسط PHP چاپ شده
        const memoryValueRaw = document.querySelector('.isp-stat-val').innerText;
        const memoryUsed = parseFloat(memoryValueRaw);
        const totalMemory = 512; // فرض بر حد استاندارد ۵۱۲ مگابایت (قابل تغییر)

        // رسم یک نمودار ساده با استفاده از Canvas (بدون نیاز به کتابخانه سنگین خارجی)
        const renderChart = (canvas, percent) => {
            const context = canvas.getContext('2d');
            const x = canvas.width / 2;
            const y = canvas.height / 2;
            const radius = 70;
            const startAngle = 0;
            const endAngle = (percent / 100) * 2 * Math.PI;

            // دایره پس‌زمینه (خاکستری)
            context.beginPath();
            context.arc(x, y, radius, 0, 2 * Math.PI);
            context.strokeStyle = '#e2e8f0';
            context.lineWidth = 15;
            context.stroke();

            // دایره مقدار مصرف شده (آبی)
            context.beginPath();
            context.arc(x, y, radius, -Math.PI / 2, endAngle - Math.PI / 2);
            context.strokeStyle = '#007cba';
            context.lineWidth = 15;
            context.lineCap = 'round';
            context.stroke();
            
            // نمایش درصد در وسط
            context.font = 'bold 20px Tahoma';
            context.fillStyle = '#1d2327';
            context.textAlign = 'center';
            context.fillText(Math.round(percent) + '%', x, y + 8);
        };

        // محاسبه درصد و رندر
        const usagePercent = (memoryUsed / totalMemory) * 100;
        renderChart(ctx, usagePercent > 100 ? 100 : usagePercent);
    }

    // ۲. سیستم به‌روزرسانی زنده (Simulation)
    // این بخش در آینده می‌تواند با AJAX به فایل class-resource-monitor متصل شود
    console.log('Iran Speed Pro: سیستم مانیتورینگ بصری فعال شد.');
});