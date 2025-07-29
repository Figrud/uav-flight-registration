<?php
// flight-list.php

// This file displays a list of registered flights, fetching data from the database.

// Include the database connection file
require_once(dirname(__FILE__) . '/../../../../plugins/flight-registration/includes/database.php');

// Fetch flight data from the database
$flights = get_flight_data(); // Assuming this function is defined in database.php

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

<div class="flight-list-container">
    <h1>Λίστα Καταχωρημένων Πτήσεων</h1>
    
    <?php if (!empty($flights)): ?>
        <table>
            <thead>
                <tr>
                    <th>Ημερομηνία</th>
                    <th>Φορέας</th>
                    <th>Ρόλος Φορέα</th>
                    <th>Τύπος UAV</th>
                    <th>Αεροσκάφος</th>
                    <th>Έναρξη Πτήσης</th>
                    <th>Διάρκεια Πτήσης (λεπτά)</th>
                    <th>Γενική/Δνση</th>
                    <th>Δ/νση Αστυν.</th>
                    <th>LoS</th>
                    <th>Τύπος Πτήσης</th>
                    <th>Σκοπός Πτήσης</th>
                    <th>Διάταξη</th>
                    <th>Παρατηρήσεις</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($flights as $flight): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($flight['DATE']); ?></td>
                        <td><?php echo htmlspecialchars($flight['OPERATOR']); ?></td>
                        <td><?php echo htmlspecialchars($flight['OPERATOR ROLE']); ?></td>
                        <td><?php echo htmlspecialchars($flight['UAV TYPE']); ?></td>
                        <td><?php echo htmlspecialchars($flight['AIRCRAFT']); ?></td>
                        <td><?php echo htmlspecialchars($flight['BEGIN_FLIGHT']); ?></td>
                        <td><?php echo htmlspecialchars($flight['FLIGHT_TIME(min)']); ?></td>
                        <td><?php echo htmlspecialchars($flight['ΓΕΝΙΚΗ/ΔΝΣΗ']); ?></td>
                        <td><?php echo htmlspecialchars($flight['Δ/ΝΣΗ_ΑΣΤΥΝ.']); ?></td>
                        <td><?php echo htmlspecialchars($flight['LoS']); ?></td>
                        <td><?php echo htmlspecialchars($flight['FLIGHT_TYPE']); ?></td>
                        <td><?php echo htmlspecialchars($flight['FLIGHT_PURPOSE']); ?></td>
                        <td><?php echo htmlspecialchars($flight['ΔΙΑΤΑΓΗ']); ?></td>
                        <td><?php echo htmlspecialchars($flight['ΠΑΡΑΤΗΡΗΣΕΙΣ']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>Δεν υπάρχουν καταχωρημένες πτήσεις.</p>
    <?php endif; ?>
</div>

</body>
</html>