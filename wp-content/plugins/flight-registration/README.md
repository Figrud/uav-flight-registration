# Σύστημα Καταχώρησης Πτήσεων UAV - Τεχνική Τεκμηρίωση

## Επισκόπηση Συστήματος

Αυτό το σύστημα είναι ένα WordPress plugin που υλοποιεί τη λειτουργικότητα που ζητήσατε:
- Βάση δεδομένων για καταχώρηση πτήσεων
- Φόρμα καταχώρησης στο WordPress
- Εξαγωγή δεδομένων σε Excel

## Δομή Αρχείων και Λειτουργικότητα

### 1. Βάση Δεδομένων

**📁 database/schema.sql**
- **Τι κάνει**: Περιέχει την SQL εντολή για τη δημιουργία του πίνακα `καταχώρηση_Πτήσεων`
- **Πώς το κάνει**: Ορίζει όλα τα πεδία που ζητήσατε με τα ελληνικά ονόματα
- **Πεδία**: DATE, OPERATOR, OPERATOR_ROLE, UAV_TYPE, AIRCRAFT, BEGIN_FLIGHT, FLIGHT_TIME, ΓΕΝΙΚΗ_ΔΝΣΗ, Δ_ΝΣΗ_ΑΣΤΥΝ, LoS, FLIGHT_TYPE, FLIGHT_PURPOSE, ΔΙΑΤΑΓΗ, ΠΑΡΑΤΗΡΗΣΕΙΣ

### 2. Κύριο Plugin

**📁 flight-registration.php**
- **Τι κάνει**: Είναι το κύριο αρχείο του WordPress plugin
- **Πώς το κάνει**: 
  - Καταχωρεί το plugin στο WordPress
  - Δημιουργεί menu item στο admin panel
  - Φορτώνει CSS/JavaScript αρχεία
  - Διαχειρίζεται AJAX calls για δυναμικές λειτουργίες

### 3. Διαχείριση Βάσης Δεδομένων

**📁 includes/database.php**
- **Τι κάνει**: Διαχειρίζεται όλες τις λειτουργίες της βάσης δεδομένων
- **Πώς το κάνει**:
  - `create_flight_table()`: Δημιουργεί τον πίνακα όταν ενεργοποιείται το plugin
  - `insert_flight_data()`: Εισάγει νέα πτήση στη βάση
  - `get_all_flights()`: Ανακτά όλες τις πτήσεις
  - `get_flight_by_id()`: Ανακτά συγκεκριμένη πτήση
  - `delete_flight()`: Διαγράφει πτήση
- **Ασφάλεια**: Χρησιμοποιεί prepared statements και sanitization

### 4. Διαχείριση Φόρμας

**📁 includes/form-handler.php**
- **Τι κάνει**: Διαχειρίζεται την υποβολή και επικύρωση της φόρμας καταχώρησης
- **Πώς το κάνει**:
  - `handle_flight_form_submission()`: Κύρια συνάρτηση που καλείται όταν υποβάλλεται η φόρμα
  - `sanitize_flight_form_data()`: Καθαρίζει τα δεδομένα για ασφάλεια
  - `validate_flight_data()`: Ελέγχει αν τα δεδομένα είναι έγκυρα
- **Έλεγχοι**: Nonce verification, δικαιώματα χρήστη, validation rules

### 5. Εξαγωγή Excel

**📁 includes/excel-export.php**
- **Τι κάνει**: Δημιουργεί αρχεία Excel (.csv) με τα δεδομένα πτήσεων
- **Πώς το κάνει**:
  - `export_flights_to_excel()`: Εξάγει όλες τις πτήσεις
  - `export_filtered_flights_to_excel()`: Εξάγει φιλτραρισμένες πτήσεις
- **Χαρακτηριστικά**: 
  - Σωστή κωδικοποίηση ελληνικών (UTF-8 BOM)
  - Ελληνικά headers στηλών
  - Automatic filename generation με timestamp

### 6. User Interface

**📁 templates/admin-page.php**
- **Τι κάνει**: Δημιουργεί το γραφικό περιβάλλον του συστήματος
- **Πώς το κάνει**:
  - **Tab 1**: Φόρμα καταχώρησης με όλα τα πεδία που ζητήσατε
  - **Tab 2**: Πίνακας με τις καταχωρημένες πτήσεις
  - **Tab 3**: Εργαλεία εξαγωγής Excel
- **Χαρακτηριστικά**:
  - Responsive design
  - Client-side validation
  - AJAX functionality για δυναμικές ενημερώσεις

## Ροή Λειτουργίας

### Καταχώρηση Νέας Πτήσης:
1. Χρήστης συμπληρώνει τη φόρμα
2. `form-handler.php` επικυρώνει τα δεδομένα
3. `database.php` εισάγει τα δεδομένα στη βάση
4. Εμφανίζεται μήνυμα επιτυχίας/σφάλματος

### Εξαγωγή σε Excel:
1. Χρήστης κάνει κλικ στο κουμπί εξαγωγής
2. `excel-export.php` ανακτά τα δεδομένα
3. Δημιουργείται CSV αρχείο με ελληνικά headers
4. Αρχείο κατεβαίνει αυτόματα

## Τεχνικές Λεπτομέρειες

### Ασφάλεια:
- WordPress nonces για CSRF protection
- Sanitization όλων των inputs
- Capability checks (manage_options)
- Prepared SQL statements

### Διεθνοποίηση:
- UTF-8 encoding για ελληνικούς χαρακτήρες
- BOM header για σωστή εμφάνιση στο Excel
- Ελληνικά labels και μηνύματα

### Performance:
- Conditional loading των scripts/styles
- AJAX για δυναμικές ενημερώσεις
- Indexed database fields

## Επόμενα Βήματα για Σχεδίαση

Όταν ολοκληρώσουμε τη βασική λειτουργικότητα, μπορούμε να προχωρήσουμε σε:
1. Custom CSS styling για όμορφο design
2. Responsive layout για mobile devices  
3. Dashboard με στατιστικά
4. Advanced filtering options
5. Bulk operations
6. Email notifications

Τα αρχεία είναι τώρα συγχρονισμένα και χρησιμοποιούν τα ελληνικά ονόματα πεδίων όπως τα ορίσατε αρχικά!
