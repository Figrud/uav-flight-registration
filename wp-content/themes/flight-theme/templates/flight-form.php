<?php
// flight-form.php

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Include the form handler to process the submission
    include_once('../../plugins/flight-registration/includes/form-handler.php');
}

?>

<!DOCTYPE html>
<html lang="el">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Καταχώρηση Πτήσεων</title>
    <link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/style.css">
</head>
<body>

<div class="flight-registration-form">
    <h1>Καταχώρηση Πτήσεων</h1>
    <form action="" method="POST">
        <label for="date">Ημερομηνία:</label>
        <input type="date" id="date" name="date" required>

        <label for="operator">Λειτουργός:</label>
        <input type="text" id="operator" name="operator" required>

        <label for="operator_role">Ρόλος Λειτουργού:</label>
        <input type="text" id="operator_role" name="operator_role" required>

        <label for="uav_type">Τύπος UAV:</label>
        <input type="text" id="uav_type" name="uav_type" required>

        <label for="aircraft">Αεροσκάφος:</label>
        <input type="text" id="aircraft" name="aircraft" required>

        <label for="begin_flight">Έναρξη Πτήσης:</label>
        <input type="time" id="begin_flight" name="begin_flight" required>

        <label for="flight_time">Διάρκεια Πτήσης (λεπτά):</label>
        <input type="number" id="flight_time" name="flight_time" required>

        <label for="general_direction">Γενική/Δνση:</label>
        <input type="text" id="general_direction" name="general_direction" required>

        <label for="direction_astyn">Δ/νση Αστυν:</label>
        <input type="text" id="direction_astyn" name="direction_astyn" required>

        <label for="los">LoS:</label>
        <input type="text" id="los" name="los" required>

        <label for="flight_type">Τύπος Πτήσης:</label>
        <input type="text" id="flight_type" name="flight_type" required>

        <label for="flight_purpose">Σκοπός Πτήσης:</label>
        <input type="text" id="flight_purpose" name="flight_purpose" required>

        <label for="diatagi">Διαταγή:</label>
        <input type="text" id="diatagi" name="diatagi" required>

        <label for="observations">Παρατηρήσεις:</label>
        <textarea id="observations" name="observations"></textarea>

        <button type="submit">Καταχώρηση Πτήσης</button>
    </form>
</div>

</body>
</html>