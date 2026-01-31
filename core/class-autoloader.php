<?php
/**
 * موتور بارگذاری خودکار کلاس‌ها (Autoloader)
 * این فایل باعث می‌شود افزونه به صورت هوشمند فایل‌های مورد نیاز را پیدا کند.
 */

// جلوگیری از دسترسی مستقیم
if (!defined('ABSPATH')) {
    exit;
}

spl_autoload_register(function ($class) {
    // ۱. بررسی اینکه آیا کلاس متعلق به این افزونه هست یا خیر (باید با ISP_ شروع شود)
    if (strpos($class, 'ISP_') !== 0) {
        return;
    }

    // ۲. تبدیل نام کلاس به مسیر فایل
    // مثال: ISP_Database_Orphan تبدیل می‌شود به database/class-orphan.php
    $parts = explode('_', strtolower($class));
    array_shift($parts); // حذف پیشوند ISP

    // نام فایل همیشه با class- شروع می‌شود
    $filename = 'class-' . array_pop($parts) . '.php';
    
    // مسیر پوشه‌های میانی (اگر وجود داشته باشد)
    $subpath = implode('/', $parts);
    
    // ۳. لیست پوشه‌های مجاز برای جستجوی کلاس
    $directories = ['core', 'modules', 'includes', 'admin'];
    
    foreach ($directories as $dir) {
        // ساخت مسیر کامل فایل
        $path = ISP_PATH . $dir . '/' . ($subpath ? $subpath . '/' : '') . $filename;
        
        // ۴. اگر فایل وجود داشت، آن را فراخوانی کن
        if (file_exists($path)) {
            require_once $path;
            return;
        }
    }
});