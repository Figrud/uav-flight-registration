<?php
/**
 * Plugin Name: Καταχώρηση Πτήσεων
 * Plugin URI: 
 * Description: Σύστημα καταχώρησης και διαχείρισης πτήσεων UAV
 * Version: 1.2.0
 * Author: LEFOS
 * Text Domain: flight-registration
 * Domain Path: /languages
 */

// Αποτρέπουμε άμεση πρόσβαση
if (!defined('ABSPATH')) {
    exit;
}

// Φόρτωση των απαραίτητων αρχείων
require_once plugin_dir_path( __FILE__ ) . 'includes/database.php';
require_once plugin_dir_path( __FILE__ ) . 'includes/form-handler.php';
require_once plugin_dir_path( __FILE__ ) . 'includes/excel-export.php';

// Create the database table on plugin activation
function flight_registration_activate() {
    create_flight_table();
}
register_activation_hook( __FILE__, 'flight_registration_activate' );

// Add a menu item for the flight registration form
function flight_registration_admin_menu() {
    add_menu_page(
        'Καταχώρηση Πτήσεων',
        'Καταχώρηση Πτήσεων',
        'manage_options',
        'flight-registration',
        'flight_registration_admin_page',
        'dashicons-airplane',
        26
    );
}
add_action( 'admin_menu', 'flight_registration_admin_menu' );

// Display the admin page
function flight_registration_admin_page() {
    include plugin_dir_path( __FILE__ ) . 'templates/admin-page.php';
}

// Enqueue admin scripts and styles
function flight_registration_admin_scripts( $hook ) {
   // Φόρτωση μόνο στις σελίδες του plugin
    if ( $hook != 'toplevel_page_flight-registration' && $hook != 'toplevel_page_flight-dashboard' ) {
        return;
    }
    
    wp_enqueue_script( 'jquery' );
    
    if ( $hook == 'toplevel_page_flight-dashboard' ) {
        // Dashboard CSS
        wp_enqueue_style( 
            'flight-dashboard-style', 
            plugin_dir_url( __FILE__ ) . 'assets/css/dashboard-style.css',
            array(),
            '1.0.0'
        );
    } else {
        // Admin form CSS (υπάρχον)
        wp_enqueue_style( 'flight-registration-admin', plugins_url( 'assets/admin.css', __FILE__ ) );
    }
    
    // JavaScript
    wp_enqueue_script( 
        'flight-registration-script', 
        plugin_dir_url( __FILE__ ) . 'assets/js/script.js',
        array( 'jquery' ),
        '1.0.0',
        true
    );

 // AJAX variables - ΧΩΡΙΣ wp_create_nonce() εδώ
    wp_localize_script( 'flight-registration-script', 'flight_ajax', array(
        'ajax_url' => admin_url( 'admin-ajax.php' ),
        'nonce' => '' // Κενό προς το παρόν
    ));
}
add_action( 'admin_enqueue_scripts', 'flight_registration_admin_scripts' );

// Dashboard Menu - ΠΡΟΣΘΕΣΕ ΑΥΤΟ μετά τη γραμμή 61
function flight_registration_dashboard_menu() {
    add_menu_page(
        'UAV Dashboard',
        'UAV Dashboard', 
        'manage_options',
        'flight-dashboard',
        'flight_registration_dashboard_page',
        'dashicons-airplane',
        25  // Πιο πάνω από το άλλο menu
    );
}
add_action( 'admin_menu', 'flight_registration_dashboard_menu' );

// Dashboard Page Callback
function flight_registration_dashboard_page() {
    require_once plugin_dir_path( __FILE__ ) . 'templates/dashboard-index.php';
}

// AJAX handler για ανανέωση λίστας πτήσεων
function ajax_refresh_flights_list() {
    // Έλεγχος δικαιωμάτων
    if (!current_user_can('manage_options')) {
        wp_die('Unauthorized');
    }
    
    // Κλήση της συνάρτησης από το database.php
    $flights = get_all_flights();
    
    if (empty($flights)) {
        echo '<tr><td colspan="15" style="text-align:center;">Δεν υπάρχουν καταχωρημένες πτήσεις.</td></tr>';
    } else {
        foreach ($flights as $flight) {
            echo '<tr>';
            echo '<td>' . esc_html($flight['DATE']) . '</td>';
            echo '<td>' . esc_html($flight['OPERATOR']) . '</td>';
            echo '<td>' . esc_html($flight['OPERATOR_ROLE']) . '</td>';
            echo '<td>' . esc_html($flight['UAV_TYPE']) . '</td>';
            echo '<td>' . esc_html($flight['AIRCRAFT']) . '</td>';
            echo '<td>' . esc_html($flight['BEGIN_FLIGHT']) . '</td>';
            echo '<td>' . esc_html($flight['FLIGHT_TIME']) . '</td>';
            echo '<td>' . esc_html($flight['ΓΕΝΙΚΗ_ΔΝΣΗ']) . '</td>';
            echo '<td>' . esc_html($flight['Δ_ΝΣΗ_ΑΣΤΥΝ']) . '</td>';
            echo '<td>' . esc_html($flight['LoS']) . '</td>';
            echo '<td>' . esc_html($flight['FLIGHT_TYPE']) . '</td>';
            echo '<td>' . esc_html($flight['FLIGHT_PURPOSE']) . '</td>';
            echo '<td>' . esc_html($flight['ΔΙΑΤΑΓΗ']) . '</td>';
            echo '<td>' . esc_html($flight['ΠΑΡΑΤΗΡΗΣΕΙΣ']) . '</td>';
            echo '<td><button class="button-link-delete" onclick="deleteFlight(' . $flight['id'] . ')">Διαγραφή</button></td>';
            echo '</tr>';
        }
    }
    
    wp_die();
}
add_action( 'wp_ajax_refresh_flights_list', 'ajax_refresh_flights_list' );

// AJAX handler για διαγραφή πτήσης
function ajax_delete_flight() {
    if ( !wp_verify_nonce( $_POST['nonce'], 'flight_ajax_nonce' ) ) {
        wp_die( 'Security check failed' );
    }
    
    if ( !current_user_can( 'manage_options' ) ) {
        wp_die( 'Unauthorized' );
    }
    
    $flight_id = intval( $_POST['flight_id'] );
    $result = delete_flight( $flight_id );
    
    if ( $result ) {
        wp_send_json_success( 'Η πτήση διαγράφηκε επιτυχώς.' );
    } else {
        wp_send_json_error( 'Σφάλμα κατά τη διαγραφή της πτήσης.' );
    }
}
add_action( 'wp_ajax_delete_flight', 'ajax_delete_flight' );

// AJAX handler για εξαγωγή σε Excel - ΔΙΟΡΘΩΜΕΝΟΣ
function ajax_export_excel() {
    // Έλεγχος nonce από GET ή POST
    $nonce = isset($_GET['nonce']) ? $_GET['nonce'] : (isset($_POST['nonce']) ? $_POST['nonce'] : '');
    
    if ( !wp_verify_nonce( $nonce, 'flight_ajax_nonce' ) ) {
        wp_die( 'Security check failed' );
    }
    
    if ( !current_user_can( 'manage_options' ) ) {
        wp_die( 'Unauthorized' );
    }
    
    // Κλήση της συνάρτησης εξαγωγής
    if ( function_exists( 'export_flights_to_excel' ) ) {
        export_flights_to_excel();
    } else {
        wp_die( 'Export function not found - Check if excel-export.php is loaded' );
    }
}
add_action( 'wp_ajax_export_excel', 'ajax_export_excel' );

// Redirect αρχικής σελίδας στο Dashboard - ΠΡΟΣΘΕΣΕ ΣΤΟΤΕΛΟΣ
function redirect_home_to_dashboard() {
    // Μόνο για την αρχική σελίδα και όχι admin
    if ( is_front_page() && !is_admin() && !is_user_logged_in() ) {
        // Redirect στο admin dashboard
        wp_redirect( admin_url('admin.php?page=flight-dashboard') );
        exit;
    }
    
    // Αν είναι συνδεδεμένος, δείξε το dashboard άμεσα
    if ( is_front_page() && !is_admin() && is_user_logged_in() ) {
        wp_redirect( admin_url('admin.php?page=flight-dashboard') );
        exit;
    }
}
add_action( 'template_redirect', 'redirect_home_to_dashboard' );

// Service Worker Registration - ΔΙΟΡΘΩΜΕΝΟ
function flight_registration_register_sw() {
    ?>
    <script>
    if ('serviceWorker' in navigator) {
        window.addEventListener('load', function() {
            navigator.serviceWorker.register('<?php echo plugin_dir_url( __FILE__ ); ?>assets/js/sw.js', {
                scope: '<?php echo admin_url('admin.php?page=flight-dashboard'); ?>'
            })
            .then(function(registration) {
                console.log('🚁 UAV Dashboard SW: Registered successfully');
                console.log('Scope:', registration.scope);
                
                // Manual install prompt
                window.addEventListener('beforeinstallprompt', (e) => {
                    console.log('🚁 PWA: Install prompt available!');
                    e.preventDefault();
                    
                    // Show custom install button
                    if (confirm('🚁 UAV Dashboard PWA\n\nΘέλετε να εγκαταστήσετε το UAV Dashboard ως εφαρμογή;\n\n✅ Offline access\n✅ Native app experience\n✅ Push notifications')) {
                        e.prompt();
                    }
                });
            })
            .catch(function(error) {
                console.log('🚁 UAV Dashboard SW: Registration failed', error);
            });
        });
    }
    </script>
    <?php
}
add_action( 'admin_footer', 'flight_registration_register_sw' );

// PWA Meta Tags και Manifest - 
function flight_registration_pwa_meta() {
    ?>
    <!-- PWA Meta Tags -->
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="UAV Dashboard">
    <meta name="theme-color" content="#764ba2">
    
    <!-- Manifest Link -->
    <link rel="manifest" href="<?php echo plugin_dir_url( __FILE__ ); ?>assets/manifest.json">
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" sizes="32x32" href="<?php echo plugin_dir_url( __FILE__ ); ?>assets/icons/icon-32x32.png">
    <?php
}
add_action( 'admin_head', 'flight_registration_pwa_meta' );
    
// Frontend Support - 


// Shortcode για Frontend Dashboard
function flight_frontend_dashboard_shortcode($atts) {
    if (!is_user_logged_in()) {
        return '<div class="flight-login-required">
            <h3>🚁 UAV Dashboard Access</h3>
            <p>Χρειάζεται σύνδεση για πρόσβαση στο Dashboard.</p>
            <a href="' . wp_login_url(get_permalink()) . '" class="btn-login">Σύνδεση</a>
            <a href="' . wp_registration_url() . '" class="btn-register">Εγγραφή</a>
        </div>';
    }
    
    ob_start();
    include plugin_dir_path(__FILE__) . 'templates/frontend-dashboard.php';
    return ob_get_clean();
}
add_shortcode('uav_dashboard', 'flight_frontend_dashboard_shortcode');



// AJAX Handler για Frontend Form Submission
function handle_frontend_flight_submission() {
    // Verify nonce
    if (!wp_verify_nonce($_POST['flight_nonce'], 'frontend_flight_nonce')) {
        wp_send_json_error(array('message' => 'Σφάλμα ασφαλείας!'));
        return;
    }
    
    if (!is_user_logged_in()) {
        wp_send_json_error(array('message' => 'Χρειάζεται σύνδεση!'));
        return;
    }
    
    $current_user = wp_get_current_user();
    
    // Sanitize data για το ΥΠΑΡΧΟΝ schema
    $flight_date = sanitize_text_field($_POST['flight_date']);
    $flight_time = sanitize_text_field($_POST['flight_time']);
    $duration = intval($_POST['duration']);
    $location = sanitize_text_field($_POST['location']);
    $uav_model = sanitize_text_field($_POST['uav_model']);
    $pilot_name = sanitize_text_field($_POST['pilot_name']);
    $purpose = sanitize_text_field($_POST['purpose']);
    $notes = sanitize_textarea_field($_POST['notes'] ?? '');
    
    global $wpdb;
    $table_name = $wpdb->prefix . 'καταχώρηση_Πτήσεων'; // ΥΠΑΡΧΟΝ table name
    
    // Insert με τα ΣΩΣΤΑ field names
    $result = $wpdb->insert(
        $table_name,
        array(
            'DATE' => $flight_date,                    // DATE ( flight_date)
            'OPERATOR' => $pilot_name,                 // OPERATOR ( pilot_name)
            'OPERATOR_ROLE' => 'Pilot',               // OPERATOR_ROLE (σταθερό)
            'UAV_TYPE' => $uav_model,                 // UAV_TYPE (uav_model)
            'AIRCRAFT' => $uav_model,                 // AIRCRAFT (ίδιο με UAV_TYPE)
            'BEGIN_FLIGHT' => $flight_time,           // BEGIN_FLIGHT ( flight_time)
            'FLIGHT_TIME' => $duration,               // FLIGHT_TIME ( duration)
            'ΓΕΝΙΚΗ_ΔΝΣΗ' => $location,               // ΓΕΝΙΚΗ_ΔΝΣΗ ( location)
            'Δ_ΝΣΗ_ΑΣΤΥΝ' => '',                     // Δ_ΝΣΗ_ΑΣΤΥΝ (άδειο)
            'LoS' => 'VLOS',                         // LoS (σταθερό)
            'FLIGHT_TYPE' => 'Operational',          // FLIGHT_TYPE (σταθερό)
            'FLIGHT_PURPOSE' => $purpose,            // FLIGHT_PURPOSE ( purpose)
            'ΔΙΑΤΑΓΗ' => '',                         // ΔΙΑΤΑΓΗ (άδειο)
            'ΠΑΡΑΤΗΡΗΣΕΙΣ' => $notes                 // ΠΑΡΑΤΗΡΗΣΕΙΣ ( notes)
        ),
        array('%s', '%s', '%s', '%s', '%s', '%s', '%d', '%s', '%s', '%s', '%s', '%s', '%s', '%s')
    );
    
    if ($result === false) {
        wp_send_json_error(array(
            'message' => 'Database Error: ' . $wpdb->last_error,
            'debug' => $wpdb->last_query
        ));
        return;
    }
    
    wp_send_json_success(array(
        'message' => 'Η πτήση καταχωρήθηκε επιτυχώς! 🚁✅'
    ));
}
add_action('wp_ajax_frontend_submit_flight', 'handle_frontend_flight_submission');





// Shortcode για Flight Registration Form
function flight_frontend_form_shortcode($atts) {
    if (!is_user_logged_in()) {
        return '<p>Χρειάζεται σύνδεση για καταχώρηση πτήσης.</p>';
    }
    
    ob_start();
    include plugin_dir_path(__FILE__) . 'templates/frontend-form.php';
    return ob_get_clean();
}
add_shortcode('uav_form', 'flight_frontend_form_shortcode');


// Frontend Support Functions -
function get_user_flights($user_id) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'καταχώρηση_Πτήσεων';
    
    // Χρησιμοποιούμε OPERATOR αντί για user_id (δεν υπάρχει user_id στο schema)
    $current_user = get_user_by('ID', $user_id);
    $operator_name = $current_user->display_name;
    
    $results = $wpdb->get_results($wpdb->prepare(
        "SELECT * FROM $table_name WHERE OPERATOR = %s ORDER BY DATE DESC",
        $operator_name
    ));
    
    return $results ? $results : array();
}

// Frontend Enqueue Scripts
function flight_registration_frontend_enqueue_scripts() {
    if (!is_admin()) {
        wp_enqueue_style(
            'flight-frontend-style',
            plugin_dir_url(__FILE__) . 'assets/css/frontend-style.css',
            array(),
            '1.2.0'
        );

        wp_enqueue_script(
            'flight-frontend-script',
            plugin_dir_url(__FILE__) . 'assets/js/frontend-script.js',
            array('jquery'),
            '1.2.0',
            true
        );

        wp_localize_script('flight-frontend-script', 'flight_frontend_ajax', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('flight_frontend_nonce')
        ));
    }
}
add_action('wp_enqueue_scripts', 'flight_registration_frontend_enqueue_scripts');

// Elementor Integration
function register_uav_elementor_widgets() {
    // Έλεγχος αν το Elementor είναι φορτωμένο
    if (!did_action('elementor/loaded')) {
        return;
    }
    
    // Έλεγχος αν το αρχείο υπάρχει
    $widget_file = plugin_dir_path(__FILE__) . 'elementor/uav-dashboard-widget.php';
    if (!file_exists($widget_file)) {
        return;
    }
    
    require_once $widget_file;
    
    // Έλεγχος αν η κλάση υπάρχει
    if (class_exists('UAV_Dashboard_Widget')) {
        \Elementor\Plugin::instance()->widgets_manager->register_widget_type(new \UAV_Dashboard_Widget());
    }
}
add_action('elementor/widgets/widgets_registered', 'register_uav_elementor_widgets');



?>