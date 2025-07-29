<?php
/**
 * Αρχείο Διαχείρισης Βάσης Δεδομένων
 * Περιέχει όλες τις συναρτήσεις για τη δημιουργία και διαχείριση του πίνακα πτήσεων
 */

// Αποτροπή άμεσης πρόσβασης
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Δημιουργία του πίνακα πτήσεων στη βάση δεδομένων
 */
function create_flight_table() {
    global $wpdb;

    $table_name = $wpdb->prefix . 'καταχώρηση_Πτήσεων';
    $charset_collate = $wpdb->get_charset_collate();

   $sql = "CREATE TABLE $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        DATE date NOT NULL,
        OPERATOR varchar(100) NOT NULL,
        OPERATOR_ROLE varchar(100) NOT NULL,
        UAV_TYPE varchar(100) NOT NULL,
        AIRCRAFT varchar(100) NOT NULL,
        BEGIN_FLIGHT time NOT NULL,
        FLIGHT_TIME int NOT NULL,
        ΓΕΝΙΚΗ_ΔΝΣΗ varchar(100) NOT NULL,
        Δ_ΝΣΗ_ΑΣΤΥΝ varchar(100) NOT NULL,
        LoS varchar(20) NOT NULL,
        FLIGHT_TYPE varchar(50) NOT NULL,
        FLIGHT_PURPOSE text NOT NULL,
        ΔΙΑΤΑΓΗ varchar(100) NOT NULL,
        ΠΑΡΑΤΗΡΗΣΕΙΣ text,
        PRIMARY KEY (id)
    ) $charset_collate;";

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta( $sql );
}

/**
 * Ανάκτηση όλων των πτήσεων από τη βάση δεδομένων
 */
function get_all_flights() {
    global $wpdb;
    
    $table_name = $wpdb->prefix . 'καταχώρηση_Πτήσεων';
    
    // Έλεγχος αν ο πίνακας υπάρχει
    $table_exists = $wpdb->get_var("SHOW TABLES LIKE '$table_name'");
    
    if (!$table_exists) {
        error_log("Πίνακας $table_name δεν υπάρχει!");
        return array();
    }
    
    $results = $wpdb->get_results( 
        "SELECT * FROM $table_name ORDER BY DATE DESC, BEGIN_FLIGHT DESC",
        ARRAY_A 
    );
    
    if ($wpdb->last_error) {
        error_log("Database Error: " . $wpdb->last_error);
        return array();
    }
    
    return $results ? $results : array();
}

/**
 * Ανάκτηση συγκεκριμένης πτήσης με βάση το ID
 */
function get_flight_by_id( $flight_id ) {
    global $wpdb;
    
    $table_name = $wpdb->prefix . 'καταχώρηση_Πτήσεων';
    
    $result = $wpdb->get_row( 
        $wpdb->prepare( "SELECT * FROM $table_name WHERE id = %d", $flight_id ),
        ARRAY_A 
    );
    
    return $result;
}

/**
 * Διαγραφή πτήσης από τη βάση δεδομένων
 */
function delete_flight( $flight_id ) {
    global $wpdb;
    
    $table_name = $wpdb->prefix . 'καταχώρηση_Πτήσεων';
    
    $result = $wpdb->delete( 
        $table_name, 
        array( 'id' => $flight_id ), 
        array( '%d' ) 
    );
    
    return $result !== false;
}

/**
 * Εμφάνιση πίνακα πτήσεων (για AJAX)
 */
function display_flights_table() {
    $flights = get_all_flights();
    
    if (empty($flights)) {
        echo '<tr><td colspan="15" style="text-align:center;">Δεν υπάρχουν καταχωρημένες πτήσεις.</td></tr>';
        return;
    }
    
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


/**
 * ΣΤΑΤΙΣΤΙΚΕΣ ΣΥΝΑΡΤΗΣΕΙΣ
 */

/**
 * Συνολικές πτήσεις
 */
function get_total_flights_count() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'καταχώρηση_Πτήσεων';
    
    $count = $wpdb->get_var("SELECT COUNT(*) FROM $table_name");
    return $count ? (int)$count : 0;
}

/**
 * Πτήσεις σήμερα
 */
function get_today_flights_count() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'καταχώρηση_Πτήσεων';
    $today = date('Y-m-d');
    
    $count = $wpdb->get_var($wpdb->prepare(
        "SELECT COUNT(*) FROM $table_name WHERE DATE = %s",
        $today
    ));
    return $count ? (int)$count : 0;
}

/**
 * Πτήσεις αυτού του μήνα
 */
function get_month_flights_count() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'καταχώρηση_Πτήσεων';
    $month_start = date('Y-m-01');
    $month_end = date('Y-m-t');
    
    $count = $wpdb->get_var($wpdb->prepare(
        "SELECT COUNT(*) FROM $table_name WHERE DATE BETWEEN %s AND %s",
        $month_start, $month_end
    ));
    return $count ? (int)$count : 0;
}

/**
 * Πρόσφατες πτήσεις
 */
function get_recent_flights($limit = 5) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'καταχώρηση_Πτήσεων';
    
    $results = $wpdb->get_results($wpdb->prepare(
        "SELECT * FROM $table_name ORDER BY DATE DESC, BEGIN_FLIGHT DESC LIMIT %d",
        $limit
    ), ARRAY_A);
    
    return $results ? $results : array();
}