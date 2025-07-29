<?php
/**
 * Script για reset password
 * Επίσκεψη: http://localhost/flight-registration-wordpress/reset-password.php
 */

// Φόρτωση WordPress
require_once('wp-config.php');
require_once('wp-includes/wp-db.php');
require_once('wp-includes/user.php');
require_once('wp-includes/pluggable.php');

// Ρυθμίσεις
$username = 'figrud';
$new_password = 'admin123';

echo "<h2>WordPress Password Reset Tool</h2>";

// Βρες τον χρήστη
$user = get_user_by('login', $username);

if (!$user) {
    echo "<p style='color:red;'>Χρήστης '$username' δεν βρέθηκε!</p>";
    
    // Εμφάνιση όλων των χρηστών
    global $wpdb;
    $users = $wpdb->get_results("SELECT ID, user_login, user_email FROM {$wpdb->users}");
    echo "<h3>Διαθέσιμοι χρήστες:</h3>";
    echo "<ul>";
    foreach ($users as $u) {
        echo "<li>ID: {$u->ID}, Username: {$u->user_login}, Email: {$u->user_email}</li>";
    }
    echo "</ul>";
} else {
    // Αλλαγή password
    wp_set_password($new_password, $user->ID);
    echo "<p style='color:green;font-size:18px;'><strong>✅ Password άλλαξε επιτυχώς!</strong></p>";
    echo "<p><strong>Username:</strong> $username</p>";
    echo "<p><strong>Νέο Password:</strong> $new_password</p>";
    echo "<p><a href='wp-admin' style='background:#0073aa; color:white; padding:10px; text-decoration:none;'>Πήγαινε στο WordPress Admin</a></p>";
}

echo "<hr>";
echo "<p><small>Μετά το επιτυχές login, διέγραψε αυτό το αρχείο για ασφάλεια!</small></p>";
?>
