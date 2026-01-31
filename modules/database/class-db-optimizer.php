<?php
/**
 * بهینه‌ساز دیتابیس (DB Optimizer)
 */

if (!defined('ABSPATH')) exit;

class ISP_DB_Optimizer {

    /**
     * اجرای دستور OPTIMIZE روی تمام جداول برای کاهش حجم فیزیکی
     */
    public function optimize_tables() {
        global $wpdb;
        $tables = $wpdb->get_results("SHOW TABLES", ARRAY_N);
        
        foreach ($tables as $table) {
            $wpdb->query("OPTIMIZE TABLE {$table[0]}");
        }
        
        return true;
    }
}