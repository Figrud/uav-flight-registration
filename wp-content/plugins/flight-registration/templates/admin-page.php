<?php
/**
 * Σελίδα Διαχείρισης Καταχώρησης Πτήσεων
 */

// Αποτροπή άμεσης πρόσβασης
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Καλούμε τον form handler
handle_flight_form_submission();
?>

<div class="wrap">
    <h1>
        <span class="dashicons dashicons-airplane" style="font-size: 30px; margin-right: 10px;"></span>
        Καταχώρηση Πτήσεων UAV
    </h1>
    
    <div id="flight-registration-container">
        <!-- Navigation Tabs -->
        <h2 class="nav-tab-wrapper">
            <a href="#flight-form" class="nav-tab nav-tab-active" data-tab="flight-form">Νέα Καταχώρηση</a>
            <a href="#flights-list" class="nav-tab" data-tab="flights-list">Λίστα Πτήσεων</a>
        </h2>

        <!-- Φόρμα Καταχώρησης -->
        <div id="flight-form" class="tab-content active">
            <h2>Καταχώρηση Νέας Πτήσης</h2>
            
            <form method="post" action="" id="flight-registration-form" class="flight-form">
                <?php wp_nonce_field('flight_form_nonce', 'flight_nonce'); ?>
                
                <table class="form-table">
                    <tbody>
                        <!-- Ημερομηνία Πτήσης -->
                        <tr>
                            <th scope="row">
                                <label for="DATE">Ημερομηνία Πτήσης <span class="required">*</span></label>
                            </th>
                            <td>
                                <input type="date" id="DATE" name="DATE" 
                                       value="<?php echo date('Y-m-d'); ?>" required class="regular-text">
                                <p class="description">Η ημερομηνία που πραγματοποιήθηκε η πτήση</p>
                            </td>
                        </tr>

                        <!-- Χειριστής -->
                        <tr>
                            <th scope="row">
                                <label for="OPERATOR">Χειριστής <span class="required">*</span></label>
                            </th>
                            <td>
                                <input type="text" id="OPERATOR" name="OPERATOR" 
                                       required class="regular-text" placeholder="Όνομα χειριστή">
                                <p class="description">Πλήρες όνομα του χειριστή του UAV</p>
                            </td>
                        </tr>

                        <!-- Ρόλος Χειριστή -->
                        <tr>
                            <th scope="row">
                                <label for="OPERATOR_ROLE">Ρόλος Χειριστή</label>
                            </th>
                            <td>
                                <select id="OPERATOR_ROLE" name="OPERATOR_ROLE" class="regular-text">
                                    <option value="">Επιλέξτε ρόλο...</option>
                                    <option value="Πιλότος">Πιλότος</option>
                                    <option value="Χειριστής">Χειριστής</option>
                                    <option value="Τεχνικός">Τεχνικός</option>
                                </select>
                            </td>
                        </tr>

                        <!-- Τύπος UAV -->
                        <tr>
                            <th scope="row">
                                <label for="UAV_TYPE">Τύπος UAV <span class="required">*</span></label>
                            </th>
                            <td>
                                <select id="UAV_TYPE" name="UAV_TYPE" required class="regular-text">
                                    <option value="">Επιλέξτε τύπο...</option>
                                    <option value="DJI Mavic 2 zoom">DJI Mavic 2 zoom</option>
                                    <option value="DJI Mavic 2">DJI Mavic 2</option>
                                    <option value="EDOMON UAS">EDOMON UAS</option>
                                    <option value="DJI Matrice 300">DJI Matrice 300</option>
                                </select>
                            </td>
                        </tr>

                        <!-- Αεροσκάφος -->
                        <tr>
                            <th scope="row">
                                <label for="AIRCRAFT">Αεροσκάφος <span class="required">*</span></label>
                            </th>
                            <td>
                                <input type="text" id="AIRCRAFT" name="AIRCRAFT" 
                                       required class="regular-text" placeholder="Κωδικός αεροσκάφους">
                            </td>
                        </tr>

                        <!-- Ώρα Έναρξης -->
                        <tr>
                            <th scope="row">
                                <label for="BEGIN_FLIGHT">Ώρα Έναρξης <span class="required">*</span></label>
                            </th>
                            <td>
                                <input type="time" id="BEGIN_FLIGHT" name="BEGIN_FLIGHT" 
                                       required class="regular-text">
                            </td>
                        </tr>

                        <!-- Διάρκεια Πτήσης -->
                        <tr>
                            <th scope="row">
                                <label for="FLIGHT_TIME">Διάρκεια Πτήσης <span class="required">*</span></label>
                            </th>
                            <td>
                                <input type="number" id="FLIGHT_TIME" name="FLIGHT_TIME" 
                                       required min="1" class="regular-text" placeholder="Λεπτά">
                            </td>
                        </tr>

                        <!-- Τύπος Πτήσης -->
                        <tr>
                            <th scope="row">
                                <label for="FLIGHT_TYPE">Τύπος Πτήσης</label>
                            </th>
                            <td>
                                <select id="FLIGHT_TYPE" name="FLIGHT_TYPE" class="regular-text">
                                    <option value="">Επιλέξτε τύπο...</option>
                                    <option value="Εκπαίδευση">Εκπαίδευση</option>
                                    <option value="Επιχειρησιακή">Επιχειρησιακή</option>
                                    <option value="Δοκιμαστική">Δοκιμαστική</option>
                                </select>
                            </td>
                        </tr>
                          <!-- ΓΕΝΙΚΗ ΔΙΕΥΘΥΝΣΗ -->
                        <tr>
                            <th scope="row">
                                <label for="ΓΕΝΙΚΗ_ΔΝΣΗ">Γενική Διεύθυνση</label>
                            </th>
                            <td>
                                <select id="ΓΕΝΙΚΗ_ΔΝΣΗ" name="ΓΕΝΙΚΗ_ΔΝΣΗ" class="regular-text">
                                    <option value="">Επιλέξτε...</option>
                                    <option value="Γ.Δ. Αττικής">Γ.Δ. Αττικής</option>
                                    <option value="Γ.Δ. Θεσσαλονίκης">Γ.Δ. Θεσσαλονίκης</option>
                                    <option value="Γ.Δ. Βορείου Ελλάδος">Γ.Δ. Βορείου Ελλάδος</option>
                                    <option value="Γ.Δ. Κεντρικής Ελλάδος">Γ.Δ. Κεντρικής Ελλάδος</option>
                                    <option value="Γ.Δ. Νοτίου Ελλάδος">Γ.Δ. Νοτίου Ελλάδος</option>
                                </select>
                            </td>
                        </tr>

                        <!-- ΔΙΕΥΘΥΝΣΗ ΑΣΤΥΝΟΜΙΑΣ -->
                        <tr>
                            <th scope="row">
                                <label for="Δ_ΝΣΗ_ΑΣΤΥΝ">Διεύθυνση Αστυνομίας</label>
                            </th>
                            <td>
                                <input type="text" id="Δ_ΝΣΗ_ΑΣΤΥΝ" name="Δ_ΝΣΗ_ΑΣΤΥΝ" 
                                       class="regular-text" placeholder="π.χ. Δ.Α. Αθηνών">
                            </td>
                        </tr>

                        <!-- LoS (Line of Sight) -->
                        <tr>
                            <th scope="row">
                                <label for="LoS">Γραμμή Οράσης (LoS)</label>
                            </th>
                            <td>
                                <select id="LoS" name="LoS" class="regular-text">
                                    <option value="">Επιλέξτε...</option>
                                    <option value="LoS">LoS (Line of Sight)</option>
                                    <option value="BLoS">BLoS (Beyond Line of Sight)</option>
                                    <option value="ELoS">ELoS (Extended Line of Sight)</option>
                                </select>
                            </td>
                        </tr>

                        <!-- Τύπος Πτήσης -->
                        <tr>
                            <th scope="row">
                                <label for="FLIGHT_TYPE">Τύπος Πτήσης</label>
                            </th>
                            <td>
                                <select id="FLIGHT_TYPE" name="FLIGHT_TYPE" class="regular-text">
                                    <option value="">Επιλέξτε τύπο...</option>
                                    <option value="Εκπαίδευση">Εκπαίδευση</option>
                                    <option value="Επιχειρησιακή">Επιχειρησιακή</option>
                                    <option value="Δοκιμαστική">Δοκιμαστική</option>
                                    <option value="Επιθεώρηση">Επιθεώρηση</option>
                                    <option value="Αναζήτηση-Διάσωση">Αναζήτηση-Διάσωση</option>
                                    <option value="Παρακολούθηση">Παρακολούθηση</option>
                                    <option value="Φωτογράφιση">Φωτογράφιση</option>
                                    <option value="Χαρτογράφηση">Χαρτογράφηση</option>
                                </select>
                            </td>
                        </tr>

                        <!-- Σκοπός Πτήσης -->
                        <tr>
                            <th scope="row">
                                <label for="FLIGHT_PURPOSE">Σκοπός Πτήσης</label>
                            </th>
                            <td>
                                <textarea id="FLIGHT_PURPOSE" name="FLIGHT_PURPOSE" 
                                         class="large-text" rows="3" 
                                         placeholder="Περιγραφή σκοπού"></textarea>
                            </td>
                        </tr>
                        <!-- ΔΙΑΤΑΓΗ -->
                        <tr>
                            <th scope="row">
                                <label for="ΔΙΑΤΑΓΗ">Διαταγή</label>
                            </th>
                            <td>
                                <input type="text" id="ΔΙΑΤΑΓΗ" name="ΔΙΑΤΑΓΗ" 
                                       class="regular-text" placeholder="Αριθμός διαταγής ή εντολής">
                                <p class="description">Αριθμός διαταγής ή εντολής που εκδόθηκε για την πτήση</p>
                            </td>
                        </tr>

                        <!-- Παρατηρήσεις -->
                        <tr>
                            <th scope="row">
                                <label for="ΠΑΡΑΤΗΡΗΣΕΙΣ">Παρατηρήσεις</label>
                            </th>
                            <td>
                                <textarea id="ΠΑΡΑΤΗΡΗΣΕΙΣ" name="ΠΑΡΑΤΗΡΗΣΕΙΣ" 
                                         class="large-text" rows="4" 
                                         placeholder="Παρατηρήσεις"></textarea>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <p class="submit">
                    <input type="submit" name="submit_flight" id="submit_flight" 
                           class="button-primary" value="Καταχώρηση Πτήσης">
                    <input type="reset" class="button-secondary" value="Επαναφορά" 
                           style="margin-left: 10px;">
                </p>
            </form>
        </div>

        <!-- Λίστα Πτήσεων -->
        <div id="flights-list" class="tab-content">
            <h2>Καταχωρημένες Πτήσεις</h2>

             <!-- Export Button - ΔΙΟΡΘΩΜΕΝΟΣ -->
<div style="margin-bottom: 15px;">
    <a href="<?php echo wp_nonce_url( admin_url('admin-ajax.php?action=export_excel'), 'flight_ajax_nonce', 'nonce' ); ?>" 
       class="button button-primary" id="export-excel-btn">
        <span class="dashicons dashicons-download" style="vertical-align: middle;"></span>
        Εξαγωγή σε Excel
    </a>
</div>
            
            <table class="wp-list-table widefat fixed striped">
                <thead>
                    <tr>
                        <th>Ημερομηνία</th>
            <table class="wp-list-table widefat fixed striped">
                <thead>
                    <tr>
                        <th>Ημερομηνία</th>
                        <th>Χειριστής</th>
                        <th>UAV</th>
                        <th>Αεροσκάφος</th>
                        <th>Ώρα</th>
                        <th>Διάρκεια</th>
                        <th>Τύπος</th>
                        <th>Ενέργειες</th>
                    </tr>
                </thead>
                <tbody id="flights-table-body">
                    <?php
                    $flights = get_all_flights();
                    if (empty($flights)) {
                        echo '<tr><td colspan="8" style="text-align:center;">Δεν υπάρχουν καταχωρημένες πτήσεις.</td></tr>';
                    } else {
                        foreach ($flights as $flight) {
                            echo '<tr>';
                            echo '<td>' . esc_html($flight['DATE']) . '</td>';
                            echo '<td>' . esc_html($flight['OPERATOR']) . '</td>';
                            echo '<td>' . esc_html($flight['UAV_TYPE']) . '</td>';
                            echo '<td>' . esc_html($flight['AIRCRAFT']) . '</td>';
                            echo '<td>' . esc_html($flight['BEGIN_FLIGHT']) . '</td>';
                            echo '<td>' . esc_html($flight['FLIGHT_TIME']) . ' λεπτά</td>';
                            echo '<td>' . esc_html($flight['FLIGHT_TYPE']) . '</td>';
                            echo '<td><button class="button-link-delete" onclick="deleteFlight(' . $flight['id'] . ')">Διαγραφή</button></td>';
                            echo '</tr>';
                        }
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
.nav-tab-wrapper { margin-bottom: 20px; }
.tab-content { display: none; }
.tab-content.active { display: block; }
.required { color: red; }
.flight-form .form-table th { width: 200px; }
</style>

<script>
jQuery(document).ready(function($) {
    // Tab switching
    $('.nav-tab').click(function(e) {
        e.preventDefault();
        
        $('.nav-tab').removeClass('nav-tab-active');
        $(this).addClass('nav-tab-active');
        
        $('.tab-content').removeClass('active');
        $('#' + $(this).data('tab')).addClass('active');
    });
});

function deleteFlight(flightId) {
    if (confirm('Είστε σίγουροι ότι θέλετε να διαγράψετε αυτή την πτήση;')) {
        // AJAX call to delete flight
        console.log('Deleting flight ID: ' + flightId);
    }
}
</script>