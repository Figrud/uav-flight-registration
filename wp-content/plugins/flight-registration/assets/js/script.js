/**
 * JavaScript για το Plugin Καταχώρησης Πτήσεων
 * Διαχειρίζεται τη λειτουργικότητα των tabs, validations και AJAX calls
 */

jQuery(document).ready(function($) {
    
    // Αρχικοποίηση όταν φορτώνει η σελίδα
    initializeFlightRegistration();
    
    /**
     * Κύρια συνάρτηση αρχικοποίησης
     */
    function initializeFlightRegistration() {
        initializeTabs();
        initializeFormValidation();
        initializeUAVTypeHandling();
        initializeExcelExport();
        initializeFlightsList();
        setDefaultValues();
    }
    
    /**
     * Διαχείριση των Tabs
     */
    function initializeTabs() {
        // Click event για τα tabs
        $('.nav-tab').on('click', function(e) {
            e.preventDefault();
            
            var targetTab = $(this).attr('href');
            
            // Αφαίρεση active class από όλα τα tabs
            $('.nav-tab').removeClass('nav-tab-active');
            $('.tab-content').removeClass('active');
            
            // Προσθήκη active class στο επιλεγμένο tab
            $(this).addClass('nav-tab-active');
            $(targetTab).addClass('active').addClass('fade-in');
            
            // Αν είναι το tab της λίστας, ανανέωση των δεδομένων
            if (targetTab === '#flight-list') {
                refreshFlightsList();
            }
        });
    }
    
    /**
     * Validation της φόρμας
     */
    function initializeFormValidation() {
        $('#flight-registration-form').on('submit', function(e) {
            if (!validateForm()) {
                e.preventDefault();
                showValidationError('Παρακαλώ διορθώστε τα σφάλματα στη φόρμα.');
                return false;
            }
            
            // Εμφάνιση loading state
            showLoadingState();
        });
        
        // Real-time validation για συγκεκριμένα πεδία
        $('#date_flight').on('change', validateDate);
        $('#begin_flight').on('change', validateTime);
        $('#flight_time_min').on('input', validateFlightTime);
        $('#operator').on('input', validateOperator);
    }
    
    /**
     * Διαχείριση του τύπου UAV (εμφάνιση "Άλλο" πεδίου)
     */
    function initializeUAVTypeHandling() {
        $('#uav_type').on('change', function() {
            var selectedValue = $(this).val();
            var otherInput = $('#uav_type_other');
            
            if (selectedValue === 'Άλλο') {
                otherInput.slideDown().addClass('slide-down').attr('required', true);
                otherInput.focus();
            } else {
                otherInput.slideUp().removeClass('slide-down').attr('required', false);
                otherInput.val('');
            }
        });
    }
    
    /**
     * Διαχείριση εξαγωγής Excel
     */
    function initializeExcelExport() {
        // Εξαγωγή όλων των πτήσεων
        $('.export-all-flights').on('click', function(e) {
            e.preventDefault();
            
            showLoadingState();
            
            // Δημιουργία κρυφής φόρμας για download
            var form = $('<form>', {
                'method': 'POST',
                'action': ajaxurl,
                'target': '_blank'
            });
            
            form.append($('<input>', {
                'type': 'hidden',
                'name': 'action',
                'value': 'export_flights_excel'
            }));
            
            form.append($('<input>', {
                'type': 'hidden',
                'name': 'nonce',
                'value': flight_ajax.nonce
            }));
            
            $('body').append(form);
            form.submit();
            form.remove();
            
            hideLoadingState();
        });
        
        // Φιλτραρισμένη εξαγωγή
        $('#filtered-export-form').on('submit', function(e) {
            e.preventDefault();
            
            var formData = {
                action: 'export_filtered_flights_excel',
                nonce: flight_ajax.nonce,
                date_from: $('input[name="date_from"]').val(),
                date_to: $('input[name="date_to"]').val(),
                operator_filter: $('input[name="operator_filter"]').val(),
                uav_type_filter: $('select[name="uav_type_filter"]').val()
            };
            
            showLoadingState();
            
            // AJAX call για φιλτραρισμένη εξαγωγή
            $.post(ajaxurl, formData, function(response) {
                if (response.success) {
                    // Trigger download
                    window.open(response.data.download_url, '_blank');
                } else {
                    showValidationError('Σφάλμα κατά την εξαγωγή: ' + response.data.message);
                }
            }).always(function() {
                hideLoadingState();
            });
        });
    }
    
    /**
     * Διαχείριση λίστας πτήσεων
     */
    function initializeFlightsList() {
        // Κουμπιά προβολής λεπτομερειών
        $(document).on('click', '.view-details', function() {
            var flightId = $(this).data('flight-id');
            showFlightDetails(flightId);
        });
        
        // Κουμπιά διαγραφής (αν τα προσθέσουμε αργότερα)
        $(document).on('click', '.delete-flight', function() {
            var flightId = $(this).data('flight-id');
            deleteFlight(flightId);
        });
    }
    
    /**
     * Ορισμός προεπιλεγμένων τιμών
     */
    function setDefaultValues() {
        // Ορισμός τρέχουσας ημερομηνίας
        if ($('#date_flight').val() === '') {
            var today = new Date().toISOString().split('T')[0];
            $('#date_flight').val(today);
        }
        
        // Ορισμός τρέχουσας ώρας
        if ($('#begin_flight').val() === '') {
            var now = new Date();
            var currentTime = now.getHours().toString().padStart(2, '0') + ':' + 
                             now.getMinutes().toString().padStart(2, '0');
            $('#begin_flight').val(currentTime);
        }
    }
    
    /**
     * Validation Functions
     */
    function validateForm() {
        var isValid = true;
        var errors = [];
        
        // Έλεγχος υποχρεωτικών πεδίων
        var requiredFields = {
            'date_flight': 'Ημερομηνία Πτήσης',
            'operator': 'Χειριστής',
            'operator_role': 'Ρόλος Χειριστή',
            'uav_type': 'Τύπος UAV',
            'aircraft': 'Αεροσκάφος',
            'begin_flight': 'Ώρα Έναρξης',
            'flight_time_min': 'Διάρκεια Πτήσης',
            'flight_type': 'Τύπος Πτήσης'
        };
        
        $.each(requiredFields, function(fieldName, fieldLabel) {
            var fieldValue = $('#' + fieldName).val();
            if (!fieldValue || fieldValue.trim() === '') {
                errors.push('Το πεδίο "' + fieldLabel + '" είναι υποχρεωτικό.');
                $('#' + fieldName).addClass('error-field');
                isValid = false;
            } else {
                $('#' + fieldName).removeClass('error-field');
            }
        });
        
        // Έλεγχος τύπου UAV "Άλλο"
        if ($('#uav_type').val() === 'Άλλο') {
            var otherValue = $('#uav_type_other').val();
            if (!otherValue || otherValue.trim() === '') {
                errors.push('Παρακαλώ καθορίστε τον τύπο UAV.');
                $('#uav_type_other').addClass('error-field');
                isValid = false;
            } else {
                $('#uav_type_other').removeClass('error-field');
            }
        }
        
        // Εμφάνιση σφαλμάτων
        if (errors.length > 0) {
            showValidationErrors(errors);
        } else {
            hideValidationErrors();
        }
        
        return isValid;
    }
    
    function validateDate() {
        var dateValue = $(this).val();
        var today = new Date().toISOString().split('T')[0];
        
        if (dateValue > today) {
            showFieldError($(this), 'Η ημερομηνία δεν μπορεί να είναι μελλοντική.');
            return false;
        }
        
        hideFieldError($(this));
        return true;
    }
    
    function validateTime() {
        var timeValue = $(this).val();
        if (timeValue && !/^([01]?[0-9]|2[0-3]):[0-5][0-9]$/.test(timeValue)) {
            showFieldError($(this), 'Μη έγκυρη μορφή ώρας.');
            return false;
        }
        
        hideFieldError($(this));
        return true;
    }
    
    function validateFlightTime() {
        var timeValue = parseInt($(this).val());
        if (timeValue <= 0 || timeValue > 1440) {
            showFieldError($(this), 'Η διάρκεια πρέπει να είναι 1-1440 λεπτά.');
            return false;
        }
        
        hideFieldError($(this));
        return true;
    }
    
    function validateOperator() {
        var operatorValue = $(this).val();
        if (operatorValue.length > 255) {
            showFieldError($(this), 'Το όνομα χειριστή είναι πολύ μεγάλο.');
            return false;
        }
        
        hideFieldError($(this));
        return true;
    }
    
    /**
     * UI Helper Functions
     */
    function showLoadingState() {
        $('#flight-registration-container').addClass('loading');
        $('input[type="submit"]').prop('disabled', true).val('Παρακαλώ περιμένετε...');
    }
    
    function hideLoadingState() {
        $('#flight-registration-container').removeClass('loading');
        $('input[type="submit"]').prop('disabled', false).val('Καταχώρηση Πτήσης');
    }
    
    function showValidationError(message) {
        var errorDiv = $('<div class="notice notice-error is-dismissible"><p>' + message + '</p></div>');
        $('#flight-registration-container').prepend(errorDiv);
        
        setTimeout(function() {
            errorDiv.fadeOut();
        }, 5000);
    }
    
    function showValidationErrors(errors) {
        var errorHtml = '<div class="validation-errors"><ul>';
        $.each(errors, function(index, error) {
            errorHtml += '<li>' + error + '</li>';
        });
        errorHtml += '</ul></div>';
        
        $('.validation-errors').remove();
        $('#flight-registration-form').before(errorHtml);
    }
    
    function hideValidationErrors() {
        $('.validation-errors').remove();
        $('.error-field').removeClass('error-field');
    }
    
    function showFieldError(field, message) {
        field.addClass('error-field');
        
        // Αφαίρεση προηγούμενου error message
        field.next('.field-error').remove();
        
        // Προσθήκη νέου error message
        field.after('<span class="field-error" style="color: #dc3232; font-size: 12px; display: block; margin-top: 5px;">' + message + '</span>');
    }
    
    function hideFieldError(field) {
        field.removeClass('error-field');
        field.next('.field-error').remove();
    }
    
    /**
     * Λειτουργίες για τη λίστα πτήσεων
     */
    function refreshFlightsList() {
        // AJAX call για ανανέωση της λίστας πτήσεων
        $.post(ajaxurl, {
            action: 'refresh_flights_list',
            nonce: flight_ajax.nonce
        }, function(response) {
            if (response.success) {
                $('.flights-table-container').html(response.data.html);
            }
        });
    }
    
    function showFlightDetails(flightId) {
        // AJAX call για λήψη λεπτομερειών πτήσης
        $.post(ajaxurl, {
            action: 'get_flight_details',
            flight_id: flightId,
            nonce: flight_ajax.nonce
        }, function(response) {
            if (response.success) {
                // Εμφάνιση modal ή popup με λεπτομέρειες
                showFlightModal(response.data.flight);
            } else {
                alert('Σφάλμα κατά τη λήψη των στοιχείων της πτήσης.');
            }
        });
    }
    
    function showFlightModal(flight) {
        // Δημιουργία modal για εμφάνιση λεπτομερειών
        var modalHtml = `
            <div id="flight-modal" class="flight-modal">
                <div class="modal-content">
                    <span class="close-modal">&times;</span>
                    <h2>Λεπτομέρειες Πτήσης #${flight.id}</h2>
                    <div class="flight-details">
                        <p><strong>Ημερομηνία:</strong> ${flight.date_flight}</p>
                        <p><strong>Χειριστής:</strong> ${flight.operator}</p>
                        <p><strong>Ρόλος:</strong> ${flight.operator_role}</p>
                        <p><strong>Τύπος UAV:</strong> ${flight.uav_type}</p>
                        <p><strong>Αεροσκάφος:</strong> ${flight.aircraft}</p>
                        <p><strong>Ώρα Έναρξης:</strong> ${flight.begin_flight}</p>
                        <p><strong>Διάρκεια:</strong> ${flight.flight_time_min} λεπτά</p>
                        <p><strong>Τύπος Πτήσης:</strong> ${flight.flight_type}</p>
                        ${flight.flight_purpose ? '<p><strong>Σκοπός:</strong> ' + flight.flight_purpose + '</p>' : ''}
                        ${flight.observations ? '<p><strong>Παρατηρήσεις:</strong> ' + flight.observations + '</p>' : ''}
                    </div>
                </div>
            </div>
        `;
        
        $('body').append(modalHtml);
        
        // Close modal events
        $('.close-modal, #flight-modal').on('click', function(e) {
            if (e.target === this) {
                $('#flight-modal').fadeOut().remove();
            }
        });
    }
    
    function deleteFlight(flightId) {
        if (confirm('Είστε σίγουροι ότι θέλετε να διαγράψετε αυτή την πτήση;')) {
            $.post(ajaxurl, {
                action: 'delete_flight',
                flight_id: flightId,
                nonce: flight_ajax.nonce
            }, function(response) {
                if (response.success) {
                    refreshFlightsList();
                    showValidationError('Η πτήση διαγράφηκε επιτυχώς.');
                } else {
                    alert('Σφάλμα κατά τη διαγραφή της πτήσης.');
                }
            });
        }
    }
    
    // Keyboard shortcuts
    $(document).on('keydown', function(e) {
        // Ctrl+S για αποθήκευση φόρμας
        if (e.ctrlKey && e.which === 83) {
            e.preventDefault();
            $('#flight-registration-form').submit();
        }
        
        // Escape για κλείσιμο modal
        if (e.which === 27) {
            $('#flight-modal').fadeOut().remove();
        }
    });
});