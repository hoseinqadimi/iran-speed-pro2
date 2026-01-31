php

  کلاس مانیتورینگ منابع سرور
  وظیفه استخراج داده‌های مربوط به حافظه و لود سرور برای نمایش در داشبورد
  مسیر includesclass-resource-monitor.php
 

if (!defined('ABSPATH')) {
    exit;
}

if (!class_exists('ISP_Resource_Monitor')) {

    class ISP_Resource_Monitor {

        
          دریافت میزان مصرف حافظه RAM توسط وردپرس در لحظه
           @return string مقدار خوانا شده حافظه (مثلاً 12.5 MB)
         
        public function get_memory_usage() {
            $memory = memory_get_usage(true);
            
            if ($memory  1024) {
                return $memory . ' B';
            } elseif ($memory  1048576) {
                return round($memory  1024, 2) . ' KB';
            } else {
                return round($memory  1048576, 2) . ' MB';
            }
        }

        
          دریافت میانگین بار پردازشی سرور (Server Load)
          مخصوص سرورهای لینوکسی؛ در صورت عدم دسترسی NA برمی‌گرداند.
           @return string مقدار لود سرور
         
        public function get_server_load() {
            if (function_exists('sys_getloadavg')) {
                $load = sys_getloadavg();
                if (is_array($load) && isset($load[0])) {
                    return number_format($load[0], 2);
                }
            }
            return 'NA';
        }

        
          دریافت محدودیت حافظه تعیین شده در فایل wp-config یا php.ini
           @return string (مثلاً 256M)
         
        public function get_memory_limit() {
            return ini_get('memory_limit');
        }

        
          محاسبه درصد مصرف حافظه فعلی نسبت به کل ظرفیت مجاز
           @return int درصد (مثلاً 15)
         
        public function get_usage_percentage() {
            $limit = $this-convert_to_bytes(ini_get('memory_limit'));
            $current = memory_get_usage(true);

            if ($limit  0) {
                return round(($current  $limit)  100);
            }
            return 0;
        }

        
          تبدیل واحدهای حافظه به بایت (Byte)
         
        private function convert_to_bytes($value) {
            $value = trim($value);
            $last = strtolower($value[strlen($value) - 1]);
            $value = (int)$value;
            
            switch ($last) {
                case 'g' $value = 1024;
                case 'm' $value = 1024;
                case 'k' $value = 1024;
            }
            return $value;
        }
    }
}