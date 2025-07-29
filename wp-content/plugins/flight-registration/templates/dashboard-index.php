<?php
/**
 * Αρχική Σελίδα Dashboard - Καταχώρηση Πτήσεων UAV
 */

// Αποτροπή άμεσης πρόσβασης
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Ανάκτηση στατιστικών - με έλεγχο αν υπάρχουν οι συναρτήσεις
$total_flights = function_exists('get_total_flights_count') ? get_total_flights_count() : 0;
$today_flights = function_exists('get_today_flights_count') ? get_today_flights_count() : 0;
$this_month_flights = function_exists('get_month_flights_count') ? get_month_flights_count() : 0;
$recent_flights = function_exists('get_recent_flights') ? get_recent_flights(5) : array();
?>

<div class="wrap flight-dashboard">
    <!-- Header Section -->
    <div class="dashboard-header">
        <h1 class="dashboard-title">
            <span class="dashicons dashicons-airplane" style="font-size: 40px; color: #fff;"></span>
            Σύστημα Καταχώρησης Πτήσεων UAV
        </h1>
        <p class="dashboard-subtitle">Διαχείριση και Καταγραφή Πτήσεων Μη Επανδρωμένων Αεροσκαφών</p>
    </div>

    <!-- Statistics Cards -->
    <div class="dashboard-stats">
        <div class="stat-card total">
            <div class="stat-icon">
                <span class="dashicons dashicons-chart-area"></span>
            </div>
            <div class="stat-content">
                <h3><?php echo number_format($total_flights); ?></h3>
                <p>Συνολικές Πτήσεις</p>
            </div>
        </div>

        <div class="stat-card today">
            <div class="stat-icon">
                <span class="dashicons dashicons-calendar-alt"></span>
            </div>
            <div class="stat-content">
                <h3><?php echo number_format($today_flights); ?></h3>
                <p>Πτήσεις Σήμερα</p>
            </div>
        </div>

        <div class="stat-card month">
            <div class="stat-icon">
                <span class="dashicons dashicons-calendar"></span>
            </div>
            <div class="stat-content">
                <h3><?php echo number_format($this_month_flights); ?></h3>
                <p>Πτήσεις Μήνα</p>
            </div>
        </div>

        <div class="stat-card actions">
            <div class="stat-icon">
                <span class="dashicons dashicons-admin-tools"></span>
            </div>
            <div class="stat-content">
                <h3>Ενέργειες</h3>
                <p>Γρήγορη Πρόσβαση</p>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
<div class="dashboard-actions">
    <div class="action-section">
        <h2>Γρήγορες Ενέργειες</h2>
        <div class="action-buttons">
            <a href="<?php echo admin_url('admin.php?page=flight-registration'); ?>" class="action-btn primary">
                <span class="dashicons dashicons-plus-alt"></span>
                Νέα Καταχώρηση Πτήσης
            </a>
            
            <a href="<?php echo admin_url('admin.php?page=flight-registration'); ?>" class="action-btn secondary" id="view-all-flights">
                <span class="dashicons dashicons-list-view"></span>
                Προβολή Όλων των Πτήσεων
            </a>
            
            <a href="<?php echo admin_url('admin-ajax.php?action=export_excel&nonce=' . wp_create_nonce('flight_ajax_nonce')); ?>" class="action-btn success">
                <span class="dashicons dashicons-download"></span>
                Εξαγωγή σε Excel
            </a>
            
            <a href="<?php echo admin_url('admin.php?page=flight-reports'); ?>" class="action-btn info" id="reports-btn">
                <span class="dashicons dashicons-chart-pie"></span>
                Αναφορές & Στατιστικά
            </a>
        </div>
    </div>
</div>
<script>
// JavaScript για σωστή λειτουργία buttons
document.addEventListener('DOMContentLoaded', function() {
    // View All Flights - πάει στο tab flights-list
    const viewAllBtn = document.getElementById('view-all-flights');
    if (viewAllBtn) {
        viewAllBtn.href = '<?php echo admin_url('admin.php?page=flight-registration&tab=flights-list'); ?>';
    }
    
    // Reports Button - προς το παρόν alert
    const reportsBtn = document.getElementById('reports-btn');
    if (reportsBtn) {
        reportsBtn.addEventListener('click', function(e) {
            e.preventDefault();
            alert('📊 Η σελίδα Αναφορών & Στατιστικών θα είναι διαθέσιμη σύντομα!\n\nΘα περιλαμβάνει:\n• Γραφήματα πτήσεων ανά μήνα\n• Στατιστικά χειριστών\n• Αναλυτικές αναφορές');
        });
    }
});
</script>

    <!-- Recent Flights -->
    <div class="dashboard-recent">
        <div class="recent-section">
            <div class="section-header">
                <h2>Πρόσφατες Πτήσεις</h2>
                <a href="<?php echo admin_url('admin.php?page=flight-registration&tab=flights-list'); ?>" class="view-all">
                    Προβολή Όλων →
                </a>
            </div>
            
            <div class="recent-flights-table">
                <?php if (!empty($recent_flights)): ?>
                    <table class="wp-list-table widefat fixed striped">
                        <thead>
                            <tr>
                                <th>Ημερομηνία</th>
                                <th>Χειριστής</th>
                                <th>UAV</th>
                                <th>Διάρκεια</th>
                                <th>Τύπος</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($recent_flights as $flight): ?>
                                <tr>
                                    <td><?php echo esc_html($flight['DATE']); ?></td>
                                    <td><?php echo esc_html($flight['OPERATOR']); ?></td>
                                    <td><?php echo esc_html($flight['UAV_TYPE']); ?></td>
                                    <td><?php echo esc_html($flight['FLIGHT_TIME']); ?> λεπτά</td>
                                    <td><?php echo esc_html($flight['FLIGHT_TYPE']); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <div class="no-flights">
                        <p>Δεν υπάρχουν καταχωρημένες πτήσεις ακόμα.</p>
                        <a href="<?php echo admin_url('admin.php?page=flight-registration'); ?>" class="button button-primary">
                            Καταχώρηση Πρώτης Πτήσης
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- System Info -->
    <div class="dashboard-info">
        <div class="info-section">
            <h2>Πληροφορίες Συστήματος</h2>
            <div class="info-grid">
                <div class="info-item">
                    <strong>Έκδοση Plugin:</strong> 1.0.0
                </div>
                <div class="info-item">
                    <strong>Τελευταία Ενημέρωση:</strong> <?php echo date('d/m/Y H:i'); ?>
                </div>
                <div class="info-item">
                    <strong>Database:</strong> wp_καταχώρηση_Πτήσεων
                </div>
                <div class="info-item">
                    <strong>Κωδικοποίηση:</strong> UTF-8
                </div>
            </div>
        </div>
    </div>
</div>