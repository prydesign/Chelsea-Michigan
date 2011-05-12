if( !defined( 'ABSPATH') && !defined('WP_UNINSTALL_PLUGIN') )
    exit();

// Delete Options
delete_option('sil_rss_version');

// Options
delete_option('sil_rss_total');
delete_option('sil_rss_multiple');
delete_option('sil_rss_date_format');
delete_option('sil_rss_pre_list');
delete_option('sil_rss_post_list');
delete_option('sil_rss_html_list');
delete_option('sil_rss_image_size_h');
delete_option('sil_rss_image_size_w');
delete_option('sil_rss_version');
delete_option('sil_rss_use_cache');
delete_option('sil_rss_show_categories');
delete_option('sil_rss_show_feed_url');

// Widget
delete_option('sil_rss_widget_title');
delete_option('sil_rss_widget_items');
delete_option('sil_rss_widget_pre_list');
delete_option('sil_rss_widget_post_list');
delete_option('sil_rss_widget_html_list');

// External RSS
delete_option('sil_rss_external_title');
delete_option('sil_rss_external_link');
delete_option('sil_rss_external_description');
delete_option('sil_rss_external_html');

// Export OPML
delete_option('sil_rss_opml_title');
delete_option('sil_rss_opml_owner');
delete_option('sil_rss_opml_email');
delete_option('sil_rss_opml_date');

// Drop Tables
$sil_rss_table_name = $wpdb->prefix . "sil_rss";
$sil_rss_table_categories = $wpdb->prefix . "sil_rss_categories";
$sil_rss_table_name_by_category = $wpdb->prefix . "sil_rss_by_category";

$sql = "DROP TABLE " . $sil_rss_table_name;
$wpdb->query($sql);

$sql = "DROP TABLE " . $sil_rss_table_categories;
$wpdb->query($sql);

$sql = "DROP TABLE " . $sil_rss_table_name_by_category;
$wpdb->query($sql);
