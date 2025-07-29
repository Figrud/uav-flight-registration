<?php
// Αποτρέπουμε άμεση πρόσβαση
if (!defined('ABSPATH')) {
    exit;
}

// Διαχείριση υποβολής φόρμας καταχώρησης πτήσης
function handle_flight_form_submission() {
    // Έλεγχος αν η φόρμα υποβλήθηκε
    if (!isset($_POST['submit_flight'])) {
        return;
    }

    // Έλεγχος nonce για ασφάλεια
    if (!isset($_POST['flight_nonce']) || !wp_verify_nonce($_POST['flight_nonce'], 'flight_form_nonce')) {
        echo '<div class="notice notice-error"><p><strong>Σφάλμα ασφαλείας!</strong> Παρακαλώ δοκιμάστε ξανά.</p></div>';
        return;
    }

    // Έλεγχος δικαιωμάτων χρήστη
    if (!current_user_can('manage_options')) {
        echo '<div class="notice notice-error"><p><strong>Δεν έχετε δικαίωμα για αυτή την ενέργεια.</strong></p></div>';
        return;
    }

    // Καθαρισμός και επικύρωση δεδομένων
    $data = sanitize_flight_data($_POST);
    $errors = validate_flight_data($data);

    if (empty($errors)) {
        // Αποθήκευση στη βάση δεδομένων
        $result = insert_flight_record($data);
        
        if ($result) {
            echo '<div class="notice notice-success"><p><strong>Επιτυχία!</strong> Η πτήση καταχωρήθηκε επιτυχώς.</p></div>';
        } else {
            echo '<div class="notice notice-error"><p><strong>Σφάλμα!</strong> Δεν ήταν δυνατή η αποθήκευση της πτήσης.</p></div>';
        }
    } else {
        // Εμφάνιση σφαλμάτων
        echo '<div class="notice notice-error">';
        foreach ($errors as $error) {
            echo '<p>' . esc_html($error) . '</p>';
        }
        echo '</div>';
    }
}

// Καθαρισμός δεδομένων φόρμας
function sanitize_flight_data($post_data) {
    return array(
        'DATE' => sanitize_text_field($post_data['DATE'] ?? ''),
        'OPERATOR' => sanitize_text_field($post_data['OPERATOR'] ?? ''),
        'OPERATOR_ROLE' => sanitize_text_field($post_data['OPERATOR_ROLE'] ?? ''),
        'UAV_TYPE' => sanitize_text_field($post_data['UAV_TYPE'] ?? ''),
        'AIRCRAFT' => sanitize_text_field($post_data['AIRCRAFT'] ?? ''),
        'BEGIN_FLIGHT' => sanitize_text_field($post_data['BEGIN_FLIGHT'] ?? ''),
        'FLIGHT_TIME' => intval($post_data['FLIGHT_TIME'] ?? 0),
        'ΓΕΝΙΚΗ_ΔΝΣΗ' => sanitize_text_field($post_data['ΓΕΝΙΚΗ_ΔΝΣΗ'] ?? ''),
        'Δ_ΝΣΗ_ΑΣΤΥΝ' => sanitize_text_field($post_data['Δ_ΝΣΗ_ΑΣΤΥΝ'] ?? ''),
        'LoS' => sanitize_text_field($post_data['LoS'] ?? ''),
        'FLIGHT_TYPE' => sanitize_text_field($post_data['FLIGHT_TYPE'] ?? ''),
        'FLIGHT_PURPOSE' => sanitize_text_field($post_data['FLIGHT_PURPOSE'] ?? ''),
        'ΔΙΑΤΑΓΗ' => sanitize_text_field($post_data['ΔΙΑΤΑΓΗ'] ?? ''),
        'ΠΑΡΑΤΗΡΗΣΕΙΣ' => sanitize_textarea_field($post_data['ΠΑΡΑΤΗΡΗΣΕΙΣ'] ?? '')
    );
}

// Επικύρωση δεδομένων φόρμας
function validate_flight_data($data) {
    $errors = array();

    if (empty($data['DATE'])) {
        $errors[] = 'Το πεδίο "Ημερομηνία Πτήσης" είναι υποχρεωτικό.';
    }

    if (empty($data['OPERATOR'])) {
        $errors[] = 'Το πεδίο "Χειριστής" είναι υποχρεωτικό.';
    }

    if (empty($data['UAV_TYPE'])) {
        $errors[] = 'Το πεδίο "Τύπος UAV" είναι υποχρεωτικό.';
    }

    if (empty($data['AIRCRAFT'])) {
        $errors[] = 'Το πεδίο "Αεροσκάφος" είναι υποχρεωτικό.';
    }

    if (empty($data['BEGIN_FLIGHT'])) {
        $errors[] = 'Το πεδίο "Ώρα Έναρξης" είναι υποχρεωτικό.';
    }

    if (empty($data['FLIGHT_TIME']) || $data['FLIGHT_TIME'] <= 0) {
        $errors[] = 'Το πεδίο "Διάρκεια Πτήσης" είναι υποχρεωτικό.';
    }

    return $errors;
}

// Εισαγωγή εγγραφής πτήσης στη βάση δεδομένων
function insert_flight_record($data) {
    global $wpdb;
    
    $table_name = $wpdb->prefix . 'καταχώρηση_Πτήσεων';
    
    $result = $wpdb->insert(
        $table_name,
        array(
            'DATE' => $data['DATE'],
            'OPERATOR' => $data['OPERATOR'],
            'OPERATOR_ROLE' => $data['OPERATOR_ROLE'],
            'UAV_TYPE' => $data['UAV_TYPE'],
            'AIRCRAFT' => $data['AIRCRAFT'],
            'BEGIN_FLIGHT' => $data['BEGIN_FLIGHT'],
            'FLIGHT_TIME' => $data['FLIGHT_TIME'],
            'ΓΕΝΙΚΗ_ΔΝΣΗ' => $data['ΓΕΝΙΚΗ_ΔΝΣΗ'],
            'Δ_ΝΣΗ_ΑΣΤΥΝ' => $data['Δ_ΝΣΗ_ΑΣΤΥΝ'],
            'LoS' => $data['LoS'],
            'FLIGHT_TYPE' => $data['FLIGHT_TYPE'],
            'FLIGHT_PURPOSE' => $data['FLIGHT_PURPOSE'],
            'ΔΙΑΤΑΓΗ' => $data['ΔΙΑΤΑΓΗ'],
            'ΠΑΡΑΤΗΡΗΣΕΙΣ' => $data['ΠΑΡΑΤΗΡΗΣΕΙΣ']
        ),
        array(
            '%s', '%s', '%s', '%s', '%s', '%s', '%d', 
            '%s', '%s', '%s', '%s', '%s', '%s', '%s'
        )
    );
    
    return $result !== false;
}
?>