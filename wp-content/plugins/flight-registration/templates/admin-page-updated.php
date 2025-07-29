<?php
/**
 * Σελίδα Διαχείρισης Καταχώρησης Πτήσεων
 * Περιέχει τη φόρμα καταχώρησης και τον πίνακα με τις υπάρχουσες πτήσεις
 * ΕΝΗΜΕΡΩΜΕΝΗ ΕΚΔΟΣΗ - ΜΕ ΕΛΛΗΝΙΚΑ ΟΝΟΜΑΤΑ ΠΕΔΙΩΝ
 */

// Αποτροπή άμεσης πρόσβασης
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>

<div class="wrap">
    <h1>
        <span class="dashicons dashicons-airplane" style="font-size: 30px; margin-right: 10px;"></span>
        Καταχώρηση Πτήσεων UAV
    </h1>
    
    <div id="flight-registration-container">
        
        <!-- Tabs Navigation -->
        <h2 class="nav-tab-wrapper">
            <a href="#flight-form" class="nav-tab nav-tab-active" id="form-tab">Νέα Καταχώρηση</a>
            <a href="#flight-list" class="nav-tab" id="list-tab">Λίστα Πτήσεων</a>
            <a href="#flight-export" class="nav-tab" id="export-tab">Εξαγωγή Δεδομένων</a>
        </h2>

        <!-- Tab 1: Φόρμα Καταχώρησης -->
        <div id="flight-form" class="tab-content active">
            <h2>Καταχώρηση Νέας Πτήσης</h2>
            
            <form method="post" action="" id="flight-registration-form" class="flight-form">
                <?php wp_nonce_field( 'flight_registration_nonce', 'flight_nonce' ); ?>
                
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
                                <label for="OPERATOR_ROLE">Ρόλος Χειριστή <span class="required">*</span></label>
                            </th>
                            <td>
                                <select id="OPERATOR_ROLE" name="OPERATOR_ROLE" required class="regular-text">
                                    <option value="">Επιλέξτε ρόλο...</option>
                                    <option value="Πιλότος">Πιλότος</option>
                                    <option value="Συν-Πιλότος">Συν-Πιλότος</option>
                                    <option value="Παρατηρητής">Παρατηρητής</option>
                                    <option value="Εκπαιδευόμενος">Εκπαιδευόμενος</option>
                                    <option value="Εκπαιδευτής">Εκπαιδευτής</option>
                                </select>
                                <p class="description">Ο ρόλος του χειριστή κατά τη διάρκεια της πτήσης</p>
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
                                    <option value="DJI Mavic Pro">DJI Mavic Pro</option>
                                    <option value="DJI Phantom 4">DJI Phantom 4</option>
                                    <option value="DJI Inspire 2">DJI Inspire 2</option>
                                    <option value="DJI Matrice 300">DJI Matrice 300</option>
                                    <option value="Parrot Anafi">Parrot Anafi</option>
                                    <option value="Yuneec Typhoon">Yuneec Typhoon</option>
                                    <option value="Άλλο">Άλλο</option>
                                </select>
                                <input type="text" id="UAV_TYPE_other" name="UAV_TYPE_other" 
                                       class="regular-text" style="display:none; margin-top:5px;" 
                                       placeholder="Καθορίστε τον τύπο UAV">
                                <p class="description">Μοντέλο και τύπος του UAV</p>
                            </td>
                        </tr>

                        <!-- Αεροσκάφος -->
                        <tr>
                            <th scope="row">
                                <label for="AIRCRAFT">Αεροσκάφος <span class="required">*</span></label>
                            </th>
                            <td>
                                <input type="text" id="AIRCRAFT" name="AIRCRAFT" 
                                       required class="regular-text" placeholder="Αριθμός/Κωδικός αεροσκάφους">
                                <p class="description">Αριθμός ταυτότητας ή κωδικός του αεροσκάφους</p>
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
                                <p class="description">Ώρα έναρξης της πτήσης (24ωρο σύστημα)</p>
                            </td>
                        </tr>

                        <!-- Διάρκεια Πτήσης -->
                        <tr>
                            <th scope="row">
                                <label for="FLIGHT_TIME">Διάρκεια Πτήσης (λεπτά) <span class="required">*</span></label>
                            </th>
                            <td>
                                <input type="number" id="FLIGHT_TIME" name="FLIGHT_TIME" 
                                       min="1" max="1440" required class="small-text">
                                <p class="description">Συνολική διάρκεια της πτήσης σε λεπτά</p>
                            </td>
                        </tr>

                        <!-- Γενική Διεύθυνση -->
                        <tr>
                            <th scope="row">
                                <label for="ΓΕΝΙΚΗ_ΔΝΣΗ">Γενική Διεύθυνση</label>
                            </th>
                            <td>
                                <input type="text" id="ΓΕΝΙΚΗ_ΔΝΣΗ" name="ΓΕΝΙΚΗ_ΔΝΣΗ" 
                                       class="regular-text" placeholder="Γενική Διεύθυνση">
                                <p class="description">Γενική διεύθυνση υπηρεσίας (προαιρετικό)</p>
                            </td>
                        </tr>

                        <!-- Διεύθυνση Αστυνομίας -->
                        <tr>
                            <th scope="row">
                                <label for="Δ_ΝΣΗ_ΑΣΤΥΝ">Διεύθυνση Αστυνομίας</label>
                            </th>
                            <td>
                                <input type="text" id="Δ_ΝΣΗ_ΑΣΤΥΝ" name="Δ_ΝΣΗ_ΑΣΤΥΝ" 
                                       class="regular-text" placeholder="Διεύθυνση Αστυνομίας">
                                <p class="description">Αρμόδια διεύθυνση αστυνομίας (προαιρετικό)</p>
                            </td>
                        </tr>

                        <!-- LoS (Line of Sight) -->
                        <tr>
                            <th scope="row">
                                <label for="LoS">LoS (Line of Sight)</label>
                            </th>
                            <td>
                                <select id="LoS" name="LoS" class="regular-text">
                                    <option value="">Επιλέξτε...</option>
                                    <option value="VLOS">VLOS (Visual Line of Sight)</option>
                                    <option value="EVLOS">EVLOS (Extended Visual Line of Sight)</option>
                                    <option value="BVLOS">BVLOS (Beyond Visual Line of Sight)</option>
                                </select>
                                <p class="description">Τύπος οπτικής επαφής με το UAV</p>
                            </td>
                        </tr>

                        <!-- Τύπος Πτήσης -->
                        <tr>
                            <th scope="row">
                                <label for="FLIGHT_TYPE">Τύπος Πτήσης <span class="required">*</span></label>
                            </th>
                            <td>
                                <select id="FLIGHT_TYPE" name="FLIGHT_TYPE" required class="regular-text">
                                    <option value="">Επιλέξτε τύπο...</option>
                                    <option value="Εκπαίδευση">Εκπαίδευση</option>
                                    <option value="Επιχειρησιακή">Επιχειρησιακή</option>
                                    <option value="Δοκιμαστική">Δοκιμαστική</option>
                                    <option value="Επιθεώρηση">Επιθεώρηση</option>
                                    <option value="Αναζήτηση-Διάσωση">Αναζήτηση-Διάσωση</option>
                                    <option value="Παρακολούθηση">Παρακολούθηση</option>
                                    <option value="Φωτογράφιση">Φωτογράφιση</option>
                                </select>
                                <p class="description">Κατηγορία της πτήσης</p>
                            </td>
                        </tr>

                        <!-- Σκοπός Πτήσης -->
                        <tr>
                            <th scope="row">
                                <label for="FLIGHT_PURPOSE">Σκοπός Πτήσης</label>
                            </th>
                            <td>
                                <textarea id="FLIGHT_PURPOSE" name="FLIGHT_PURPOSE" 
                                          rows="4" cols="50" class="large-text" 
                                          placeholder="Περιγράψτε τον σκοπό της πτήσης..."></textarea>
                                <p class="description">Λεπτομερής περιγραφή του σκοπού της πτήσης</p>
                            </td>
                        </tr>

                        <!-- Διαταγή -->
                        <tr>
                            <th scope="row">
                                <label for="ΔΙΑΤΑΓΗ">Διαταγή</label>
                            </th>
                            <td>
                                <input type="text" id="ΔΙΑΤΑΓΗ" name="ΔΙΑΤΑΓΗ" 
                                       class="regular-text" placeholder="Αριθμός διαταγής/εντολής">
                                <p class="description">Αριθμός διαταγής ή εντολής που αφορά την πτήση</p>
                            </td>
                        </tr>

                        <!-- Παρατηρήσεις -->
                        <tr>
                            <th scope="row">
                                <label for="ΠΑΡΑΤΗΡΗΣΕΙΣ">Παρατηρήσεις</label>
                            </th>
                            <td>
                                <textarea id="ΠΑΡΑΤΗΡΗΣΕΙΣ" name="ΠΑΡΑΤΗΡΗΣΕΙΣ" 
                                          rows="4" cols="50" class="large-text" 
                                          placeholder="Τυχόν παρατηρήσεις ή σχόλια..."></textarea>
                                <p class="description">Επιπλέον παρατηρήσεις, σχόλια ή σημειώσεις</p>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <p class="submit">
                    <input type="submit" name="submit_flight" id="submit_flight" 
                           class="button-primary" value="Καταχώρηση Πτήσης">
                    <input type="reset" class="button-secondary" value="Επαναφορά Φόρμας" 
                           style="margin-left: 10px;">
                </p>
            </form>
        </div>

        <!-- Tab 2: Λίστα Πτήσεων -->
        <div id="flight-list" class="tab-content">
            <h2>Καταχωρημένες Πτήσεις</h2>
            <?php display_flights_table_updated(); ?>
        </div>

        <!-- Tab 3: Εξαγωγή Δεδομένων -->
        <div id="flight-export" class="tab-content">
            <h2>Εξαγωγή Δεδομένων σε Excel</h2>
            
            <div class="export-section">
                <h3>Εξαγωγή Όλων των Πτήσεων</h3>
                <p>Εξαγωγή όλων των καταχωρημένων πτήσεων σε αρχείο Excel (.csv)</p>
                <a href="<?php echo get_excel_export_url(); ?>" class="button-primary">
                    <span class="dashicons dashicons-download"></span> Εξαγωγή Όλων
                </a>
            </div>

            <hr>

            <div class="export-section">
                <h3>Φιλτραρισμένη Εξαγωγή</h3>
                <form id="filtered-export-form" method="post">
                    <table class="form-table">
                        <tbody>
                            <tr>
                                <th scope="row">Από Ημερομηνία</th>
                                <td><input type="date" name="date_from" class="regular-text"></td>
                            </tr>
                            <tr>
                                <th scope="row">Έως Ημερομηνία</th>
                                <td><input type="date" name="date_to" class="regular-text"></td>
                            </tr>
                            <tr>
                                <th scope="row">Χειριστής</th>
                                <td><input type="text" name="operator_filter" class="regular-text" placeholder="Όνομα χειριστή"></td>
                            </tr>
                            <tr>
                                <th scope="row">Τύπος UAV</th>
                                <td>
                                    <select name="uav_type_filter" class="regular-text">
                                        <option value="">Όλοι οι τύποι</option>
                                        <option value="DJI Mavic Pro">DJI Mavic Pro</option>
                                        <option value="DJI Phantom 4">DJI Phantom 4</option>
                                        <option value="DJI Inspire 2">DJI Inspire 2</option>
                                        <option value="DJI Matrice 300">DJI Matrice 300</option>
                                    </select>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <p class="submit">
                        <button type="submit" class="button-primary">
                            <span class="dashicons dashicons-download"></span> Εξαγωγή Φιλτραρισμένων
                        </button>
                    </p>
                </form>
            </div>
        </div>
    </div>
</div>

<?php
/**
 * Εμφάνιση πίνακα με τις πτήσεις - Ενημερωμένη έκδοση
 */
function display_flights_table_updated() {
    $flights = get_all_flights();
    
    if ( empty( $flights ) ) {
        echo '<div class="notice notice-info">';
        echo '<p>Δεν υπάρχουν καταχωρημένες πτήσεις.</p>';
        echo '</div>';
        return;
    }
    ?>
    
    <div class="flights-table-container">
        <table class="wp-list-table widefat fixed striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Ημερομηνία</th>
                    <th>Χειριστής</th>
                    <th>Ρόλος</th>
                    <th>UAV</th>
                    <th>Αεροσκάφος</th>
                    <th>Ώρα</th>
                    <th>Διάρκεια</th>
                    <th>Τύπος</th>
                    <th>Ενέργειες</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ( $flights as $flight ): ?>
                <tr>
                    <td><?php echo esc_html( $flight['id'] ); ?></td>
                    <td><?php echo esc_html( $flight['DATE'] ); ?></td>
                    <td><?php echo esc_html( $flight['OPERATOR'] ); ?></td>
                    <td><?php echo esc_html( $flight['OPERATOR_ROLE'] ); ?></td>
                    <td><?php echo esc_html( $flight['UAV_TYPE'] ); ?></td>
                    <td><?php echo esc_html( $flight['AIRCRAFT'] ); ?></td>
                    <td><?php echo esc_html( $flight['BEGIN_FLIGHT'] ); ?></td>
                    <td><?php echo esc_html( $flight['FLIGHT_TIME'] ); ?> λεπτά</td>
                    <td><?php echo esc_html( $flight['FLIGHT_TYPE'] ); ?></td>
                    <td>
                        <button class="button-small view-details" data-flight-id="<?php echo $flight['id']; ?>">
                            Προβολή
                        </button>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    
    <?php
}
?>
