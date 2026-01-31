<?php
/**
 * سیستم پیشرفته به تاخیر انداختن اسکریپت‌ها
 * بر اساس متد تعامل کاربر (User Interaction Method)
 */

if (!defined('ABSPATH')) exit;

class ISP_Delay_JS {

    public function init() {
        if (is_admin()) return;
        add_filter('script_loader_tag', array($this, 'convert_to_lazy_load'), 99, 3);
        add_action('wp_footer', array($this, 'insert_js_executor'), 999);
    }

    public function convert_to_lazy_load($tag, $handle, $src) {
        // لیست اسکریپت‌های حیاتی که نباید به تاخیر بیفتند
        $excluded_handles = array('jquery-core', 'jquery-migrate', 'isp-main-script');
        
        if (in_array($handle, $excluded_handles)) {
            return $tag;
        }

        // تبدیل src به data-src و تغییر نوع اسکریپت برای جلوگیری از اجرا
        $tag = str_replace(' src=', ' data-isp-src=', $tag);
        $tag = str_replace('type="text/javascript"', 'type="text/isplazy"', $tag);
        $tag = str_replace("type='text/javascript'", 'type="text/isplazy"', $tag);
        
        if (strpos($tag, 'type=') === false) {
            $tag = str_replace('<script ', '<script type="text/isplazy" ', $tag);
        }

        return $tag;
    }

    public function insert_js_executor() {
        ?>
        <script id="isp-js-loader">
            (function() {
                const events = ['keydown', 'mousemove', 'touchstart', 'scroll', 'wheel'];
                const loadScripts = function() {
                    document.querySelectorAll('script[type="text/isplazy"]').forEach(script => {
                        script.type = 'text/javascript';
                        if (script.dataset.ispSrc) {
                            script.src = script.dataset.ispSrc;
                        }
                    });
                    events.forEach(e => window.removeEventListener(e, loadScripts));
                };
                events.forEach(e => window.addEventListener(e, loadScripts, {passive: true}));
            })();
        </script>
        <?php
    }
}