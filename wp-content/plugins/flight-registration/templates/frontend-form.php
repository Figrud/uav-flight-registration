<?php

// Frontend Flight Registration Form
$current_user = wp_get_current_user();
?>

<div class="uav-frontend-form">
    <div class="form-header">
        <h2>✈️ Καταχώρηση Νέας Πτήσης</h2>
        <p>Συμπληρώστε τα στοιχεία της πτήσης παρακάτω:</p>
    </div>

    <form id="frontend-flight-form" method="post" action="">
        <?php wp_nonce_field('frontend_flight_nonce', 'flight_nonce'); ?>
        
        <div class="form-grid">
            <div class="form-group">
                <label for="flight_date">📅 Ημερομηνία Πτήσης *</label>
                <input type="date" id="flight_date" name="flight_date" required>
            </div>
            
            <div class="form-group">
                <label for="flight_time">⏰ Ώρα Έναρξης *</label>
                <input type="time" id="flight_time" name="flight_time" required>
            </div>
            
            <div class="form-group">
                <label for="duration">⏱️ Διάρκεια (λεπτά) *</label>
                <input type="number" id="duration" name="duration" min="1" max="999" required>
            </div>
            
            <div class="form-group">
                <label for="location">📍 Τοποθεσία *</label>
                <input type="text" id="location" name="location" placeholder="π.χ. Αθήνα, Κέντρο" required>
            </div>
            
            <div class="form-group">
                <label for="uav_model">🚁 Μοντέλο UAV *</label>
                <select id="uav_model" name="uav_model" required>
                    <option value="">Επιλέξτε UAV</option>
                    <option value="DJI Mavic Air 2">DJI Mavic Air 2</option>
                    <option value="DJI Mini 2">DJI Mini 2</option>
                    <option value="DJI Phantom 4">DJI Phantom 4</option>
                    <option value="Parrot Anafi">Parrot Anafi</option>
                    <option value="Άλλο">Άλλο</option>
                </select>
            </div>
            
            <div class="form-group">
                <label for="pilot_name">👨‍✈️ Όνομα Χειριστή *</label>
                <input type="text" id="pilot_name" name="pilot_name" value="<?php echo esc_attr($current_user->display_name); ?>" required>
            </div>
            
            <div class="form-group full-width">
                <label for="purpose">🎯 Σκοπός Πτήσης *</label>
                <select id="purpose" name="purpose" required>
                    <option value="">Επιλέξτε σκοπό</option>
                    <option value="Αστυνομική Επιχείρηση">Αστυνομική Επιχείρηση</option>
                    <option value="Επιτήρηση Περιοχής">Επιτήρηση Περιοχής</option>
                    <option value="Αναζήτηση & Διάσωση">Αναζήτηση & Διάσωση</option>
                    <option value="Τροχαία Ατυχήματα">Τροχαία Ατυχήματα</option>
                    <option value="Εκπαίδευση">Εκπαίδευση</option>
                    <option value="Δοκιμαστική Πτήση">Δοκιμαστική Πτήση</option>
                    <option value="Άλλο">Άλλο</option>
                </select>
            </div>
            
            <div class="form-group full-width">
                <label for="notes">📝 Παρατηρήσεις</label>
                <textarea id="notes" name="notes" rows="3" placeholder="Προαιρετικές παρατηρήσεις..."></textarea>
            </div>
        </div>
        
        <div class="form-actions">
            <button type="submit" class="btn-submit">
                🚀 Καταχώρηση Πτήσης
            </button>
            <button type="reset" class="btn-reset">
                🔄 Επαναφορά
            </button>
            <a href="<?php echo remove_query_arg('action'); ?>" class="btn-cancel">
                ↩️ Επιστροφή
            </a>
        </div>
    </form>
    
    <div id="form-messages"></div>
</div>

<style>
.uav-frontend-form {
    max-width: 800px;
    margin: 0 auto;
    background: white;
    padding: 30px;
    border-radius: 10px;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
}

.form-header {
    text-align: center;
    margin-bottom: 30px;
    padding-bottom: 20px;
    border-bottom: 2px solid #f0f0f0;
}

.form-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
    margin-bottom: 30px;
}

.form-group {
    display: flex;
    flex-direction: column;
}

.form-group.full-width {
    grid-column: 1 / -1;
}

.form-group label {
    font-weight: bold;
    margin-bottom: 8px;
    color: #333;
}

.form-group input,
.form-group select,
.form-group textarea {
    padding: 12px;
    border: 2px solid #ddd;
    border-radius: 5px;
    font-size: 16px;
    transition: border-color 0.3s;
}

.form-group input:focus,
.form-group select:focus,
.form-group textarea:focus {
    outline: none;
    border-color: #764ba2;
    box-shadow: 0 0 0 3px rgba(118, 75, 162, 0.1);
}

.form-actions {
    display: flex;
    gap: 15px;
    justify-content: center;
    flex-wrap: wrap;
}

.btn-submit,
.btn-reset,
.btn-cancel {
    padding: 15px 30px;
    border: none;
    border-radius: 8px;
    font-weight: bold;
    font-size: 16px;
    cursor: pointer;
    text-decoration: none;
    display: inline-block;
    transition: all 0.3s;
}

.btn-submit {
    background: linear-gradient(45deg, #4CAF50, #45a049);
    color: white;
}

.btn-reset {
    background: linear-gradient(45deg, #ff9800, #f57c00);
    color: white;
}

.btn-cancel {
    background: linear-gradient(45deg, #607d8b, #455a64);
    color: white;
}

.btn-submit:hover,
.btn-reset:hover,
.btn-cancel:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.2);
}

@media (max-width: 768px) {
    .form-grid {
        grid-template-columns: 1fr;
    }
    
    .form-actions {
        flex-direction: column;
    }
}
</style>

<script>
document.getElementById('frontend-flight-form').addEventListener('submit', function(e) {
    e.preventDefault();
    
    // Show loading
    const submitBtn = document.querySelector('.btn-submit');
    const originalText = submitBtn.innerHTML;
    submitBtn.innerHTML = '⏳ Καταχώρηση...';
    submitBtn.disabled = true;
    
    // Collect form data
    const formData = new FormData(this);
    formData.append('action', 'frontend_submit_flight');
    
    // AJAX Submit
    fetch('<?php echo admin_url('admin-ajax.php'); ?>', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        const messagesDiv = document.getElementById('form-messages');
        
        if (data.success) {
            messagesDiv.innerHTML = '<div style="background: #d4edda; color: #155724; padding: 15px; border-radius: 5px; margin-top: 15px;">✅ ' + data.data.message + '</div>';
            this.reset();
            
            // Redirect after success
            setTimeout(() => {
                window.location.href = '<?php echo remove_query_arg('action'); ?>';
            }, 2000);
        } else {
            messagesDiv.innerHTML = '<div style="background: #f8d7da; color: #721c24; padding: 15px; border-radius: 5px; margin-top: 15px;">❌ ' + data.data.message + '</div>';
        }
        
        // Reset button
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
    })
    .catch(error => {
        document.getElementById('form-messages').innerHTML = '<div style="background: #f8d7da; color: #721c24; padding: 15px; border-radius: 5px; margin-top: 15px;">❌ Σφάλμα σύνδεσης</div>';
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
    });
});
</script>