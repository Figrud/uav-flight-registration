<?php 
function export_flights_to_excel() {
    // Έλεγχος δικαιωμάτων
    if ( ! current_user_can( 'manage_options' ) ) {
        wp_die( 'Δεν έχετε τα απαραίτητα δικαιώματα για εξαγωγή δεδομένων.' );
    }

    // Ανάκτηση όλων των πτήσεων από τη βάση δεδομένων
    $flights = get_all_flights();

    if ( empty( $flights ) ) {
        wp_die( 'Δεν υπάρχουν δεδομένα πτήσεων για εξαγωγή.' );
    }

    // Ρύθμιση headers για download αρχείου - ΝΕΟΣ ΤΡΟΠΟΣ
    $filename = 'Καταχώρηση_Πτήσεων_' . date( 'Y-m-d_H-i-s' ) . '.xls';
    
    // ΑΛΛΑΓΗ: Δημιουργούμε ΠΡΑΓΜΑΤΙΚΟ Excel αρχείο
    header( 'Content-Type: application/vnd.ms-excel; charset=utf-8' );
    header( 'Content-Disposition: attachment; filename="' . $filename . '"' );
    header( 'Pragma: no-cache' );
    header( 'Expires: 0' );

    // Δημιουργία Excel με HTML table
    echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';
    echo '<table border="1">';
    
    // Headers - ΕΛΛΗΝΙΚΑ
    echo '<tr style="background-color: #f2f2f2; font-weight: bold;">';
    echo '<th>ID</th>';
    echo '<th>Ημερομηνία</th>';
    echo '<th>Χειριστής</th>';
    echo '<th>Ρόλος</th>';
    echo '<th>Τύπος UAV</th>';
    echo '<th>Αεροσκάφος</th>';
    echo '<th>Ώρα Έναρξης</th>';
    echo '<th>Διάρκεια (λεπτά)</th>';
    echo '<th>Γενική Διεύθυνση</th>';
    echo '<th>Διεύθυνση Αστυνομίας</th>';
    echo '<th>Γραμμή Οράσης</th>';
    echo '<th>Τύπος Πτήσης</th>';
    echo '<th>Σκοπός</th>';
    echo '<th>Διαταγή</th>';
    echo '<th>Παρατηρήσεις</th>';
    echo '</tr>';

    // Δεδομένα - ΧΩΡΙΣ encoding problems
    foreach ( $flights as $flight ) {
        echo '<tr>';
        echo '<td>' . htmlspecialchars($flight['id'], ENT_QUOTES, 'UTF-8') . '</td>';
        echo '<td>' . htmlspecialchars($flight['DATE'], ENT_QUOTES, 'UTF-8') . '</td>';
        echo '<td>' . htmlspecialchars($flight['OPERATOR'], ENT_QUOTES, 'UTF-8') . '</td>';
        echo '<td>' . htmlspecialchars($flight['OPERATOR_ROLE'], ENT_QUOTES, 'UTF-8') . '</td>';
        echo '<td>' . htmlspecialchars($flight['UAV_TYPE'], ENT_QUOTES, 'UTF-8') . '</td>';
        echo '<td>' . htmlspecialchars($flight['AIRCRAFT'], ENT_QUOTES, 'UTF-8') . '</td>';
        echo '<td>' . htmlspecialchars($flight['BEGIN_FLIGHT'], ENT_QUOTES, 'UTF-8') . '</td>';
        echo '<td>' . htmlspecialchars($flight['FLIGHT_TIME'], ENT_QUOTES, 'UTF-8') . '</td>';
        echo '<td>' . htmlspecialchars($flight['ΓΕΝΙΚΗ_ΔΝΣΗ'], ENT_QUOTES, 'UTF-8') . '</td>';
        echo '<td>' . htmlspecialchars($flight['Δ_ΝΣΗ_ΑΣΤΥΝ'], ENT_QUOTES, 'UTF-8') . '</td>';
        echo '<td>' . htmlspecialchars($flight['LoS'], ENT_QUOTES, 'UTF-8') . '</td>';
        echo '<td>' . htmlspecialchars($flight['FLIGHT_TYPE'], ENT_QUOTES, 'UTF-8') . '</td>';
        echo '<td>' . htmlspecialchars($flight['FLIGHT_PURPOSE'], ENT_QUOTES, 'UTF-8') . '</td>';
        echo '<td>' . htmlspecialchars($flight['ΔΙΑΤΑΓΗ'], ENT_QUOTES, 'UTF-8') . '</td>';
        echo '<td>' . htmlspecialchars($flight['ΠΑΡΑΤΗΡΗΣΕΙΣ'], ENT_QUOTES, 'UTF-8') . '</td>';
        echo '</tr>';
    }
    
    echo '</table>';
    
    // Τερματισμός
    exit;
}
/**
 * Συνάρτηση για ανάκτηση φιλτραρισμένων πτήσεων
 * @param array $filters - Τα φίλτρα αναζήτησης
 * @return array - Πίνακας με τις πτήσεις
 */
function get_filtered_flights( $filters ) {
    global $wpdb;
    
    $table_name = $wpdb->prefix . 'καταχώρηση_Πτήσεων';
    
    $where_conditions = array();
    $where_values = array();
    
    // Φίλτρο ημερομηνίας από
    if ( ! empty( $filters['date_from'] ) ) {
        $where_conditions[] = "date_flight >= %s";
        $where_values[] = $filters['date_from'];
    }
    
    // Φίλτρο ημερομηνίας έως
    if ( ! empty( $filters['date_to'] ) ) {
        $where_conditions[] = "date_flight <= %s";
        $where_values[] = $filters['date_to'];
    }
    
    // Φίλτρο χειριστή
    if ( ! empty( $filters['operator'] ) ) {
        $where_conditions[] = "operator LIKE %s";
        $where_values[] = '%' . $filters['operator'] . '%';
    }
    
    // Φίλτρο τύπου UAV
    if ( ! empty( $filters['uav_type'] ) ) {
        $where_conditions[] = "uav_type = %s";
        $where_values[] = $filters['uav_type'];
    }
    
    // Δημιουργία του SQL query
    $sql = "SELECT * FROM $table_name";
    
    if ( ! empty( $where_conditions ) ) {
        $sql .= " WHERE " . implode( ' AND ', $where_conditions );
    }
    
    $sql .= " ORDER BY date_flight DESC, begin_flight DESC";
    
    // Εκτέλεση του query
    if ( ! empty( $where_values ) ) {
        $results = $wpdb->get_results( $wpdb->prepare( $sql, $where_values ), ARRAY_A );
    } else {
        $results = $wpdb->get_results( $sql, ARRAY_A );
    }
    
    return $results ? $results : array();
}

/**
 * Μορφοποίηση ημερομηνίας για Excel
 * @param string $date - Η ημερομηνία σε MySQL format
 * @return string - Η ημερομηνία σε ελληνικό format
 */
function format_date_for_excel( $date ) {
    if ( empty( $date ) || $date === '0000-00-00' ) {
        return '';
    }
    
    $datetime = DateTime::createFromFormat( 'Y-m-d', $date );
    return $datetime ? $datetime->format( 'd/m/Y' ) : $date;
}

/**
 * Μορφοποίηση ημερομηνίας-ώρας για Excel
 * @param string $datetime - Η ημερομηνία-ώρα σε MySQL format
 * @return string - Η ημερομηνία-ώρα σε ελληνικό format
 */
function format_datetime_for_excel( $datetime ) {
    if ( empty( $datetime ) || $datetime === '0000-00-00 00:00:00' ) {
        return '';
    }
    
    $dt = DateTime::createFromFormat( 'Y-m-d H:i:s', $datetime );
    return $dt ? $dt->format( 'd/m/Y H:i' ) : $datetime;
}

/**
 * Δημιουργία URL για εξαγωγή Excel
 * @return string - Το URL για την εξαγωγή
 */
function get_excel_export_url() {
    return admin_url( 'admin-ajax.php?action=export_flights_excel&nonce=' . wp_create_nonce( 'excel_export_nonce' ) );
}
?>