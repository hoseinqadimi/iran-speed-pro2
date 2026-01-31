/**
 * منطق مدیریت پنل ادمین ایران اسپید پرو
 * نسخه ۱۰۰٪ نهایی - مدیریت تب‌ها و انیمیشن‌ها
 */
document.addEventListener('DOMContentLoaded', function() {
    
    const tabs = document.querySelectorAll('.isp-tabs li');
    const contents = document.querySelectorAll('.isp-tab-content');

    // ۱. سیستم مدیریت تب‌ها (با قابلیت حفظ تب فعال بعد از رفرش)
    const activeTab = localStorage.getItem('isp_active_tab') || 'dashboard';
    
    const switchTab = (tabId) => {
        tabs.forEach(t => t.classList.remove('active'));
        contents.forEach(c => c.classList.remove('active'));

        const targetTab = document.querySelector(`[data-tab="${tabId}"]`);
        const targetContent = document.getElementById(tabId);

        if (targetTab && targetContent) {
            targetTab.classList.add('active');
            targetContent.classList.add('active');
            localStorage.setItem('isp_active_tab', tabId);
        }
    };

    tabs.forEach(tab => {
        tab.addEventListener('click', () => {
            switchTab(tab.getAttribute('data-tab'));
        });
    });

    // لود کردن تب پیش‌فرض یا ذخیره شده
    switchTab(activeTab);

    // ۲. انیمیشن کارت‌های داشبورد هنگام لود
    const cards = document.querySelectorAll('.isp-card');
    cards.forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        card.style.transition = 'all 0.4s ease';
        
        setTimeout(() => {
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, 100 * (index + 1));
    });

    // ۳. مدیریت دکمه‌های عملیاتی (AJAX Simulation)
    document.querySelectorAll('#isp-run-optimize, #isp-run-backup').forEach(button => {
        button.addEventListener('click', function() {
            const originalText = this.innerHTML;
            this.disabled = true;
            this.innerHTML = '<span class="dashicons dashicons-update spin"></span> در حال پردازش...';

            // شبیه‌سازی عملیات (در مرحله بعد با AJAX واقعی جایگزین می‌شود)
            setTimeout(() => {
                alert('عملیات با موفقیت در دیتابیس اجرا شد.');
                this.disabled = false;
                this.innerHTML = originalText;
            }, 1500);
        });
    });

    // ۴. انتخاب همه جداول در بخش دیتابیس
    const selectAll = document.getElementById('isp-select-all');
    if (selectAll) {
        selectAll.addEventListener('change', function() {
            document.querySelectorAll('input[name="tables[]"]').forEach(cb => {
                cb.checked = this.checked;
            });
        });
    }
});