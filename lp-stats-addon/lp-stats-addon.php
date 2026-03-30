<?php
/**
 * Plugin Name: LearnPress Stats Dashboard
 * Description: Thống kê LearnPress
 * Version: 1.0
 * Author: Nguyen Van Cong
 */

if (!defined('ABSPATH')) exit;

/* =========================
   1. Tổng số khóa học
========================= */
function lp_total_courses() {
    global $wpdb;

    return $wpdb->get_var("
        SELECT COUNT(ID)
        FROM {$wpdb->posts}
        WHERE post_type = 'lp_course'
        AND post_status = 'publish'
    ");
}

/* =========================
   2. Tổng số học viên
========================= */
function lp_total_students() {
    global $wpdb;

    return $wpdb->get_var("
        SELECT COUNT(DISTINCT user_id)
        FROM {$wpdb->prefix}learnpress_user_items
        WHERE item_type = 'lp_course'
    ");
}

/* =========================
   3. Khóa học hoàn thành
========================= */
function lp_total_completed() {
    global $wpdb;

    return $wpdb->get_var("
        SELECT COUNT(*)
        FROM {$wpdb->prefix}learnpress_user_items
        WHERE status = 'completed'
        AND item_type = 'lp_course'
    ");
}

/* =========================
   4. Dashboard Widget
========================= */
function lp_add_dashboard_widget() {
    wp_add_dashboard_widget(
        'lp_stats_widget',
        '📊 LearnPress Stats',
        'lp_dashboard_widget_content'
    );
}
add_action('wp_dashboard_setup', 'lp_add_dashboard_widget');

/* =========================
   5. Nội dung Dashboard
========================= */
function lp_dashboard_widget_content() {
    echo "<p><strong>Tổng khóa học:</strong> " . lp_total_courses() . "</p>";
    echo "<p><strong>Tổng học viên:</strong> " . lp_total_students() . "</p>";
    echo "<p><strong>Đã hoàn thành:</strong> " . lp_total_completed() . "</p>";
}

/* =========================
   6. Shortcode frontend
========================= */
function lp_stats_shortcode() {
    ob_start();
    ?>
    <div style="border:1px solid #ccc; padding:15px;">
        <h3>📊 Thống kê LearnPress</h3>
        <p>Tổng khóa học: <?php echo lp_total_courses(); ?></p>
        <p>Tổng học viên: <?php echo lp_total_students(); ?></p>
        <p>Khóa hoàn thành: <?php echo lp_total_completed(); ?></p>
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode('lp_total_stats', 'lp_stats_shortcode');