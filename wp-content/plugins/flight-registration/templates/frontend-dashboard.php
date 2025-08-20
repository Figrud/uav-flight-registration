<?php
// Frontend Dashboard Template
$current_user = wp_get_current_user();
$user_flights = get_user_flights($current_user->ID);
$total_flights = count($user_flights);
$this_year_flights = 0;

// Count flights από φέτος
foreach($user_flights as $flight) {
    if(date('Y', strtotime($flight->flight_date)) == date('Y')) {
        $this_year_flights++;
    }
}
?>

<div class="uav-frontend-dashboard">
    <header class="dashboard-header">
        <h1>🚁 UAV Dashboard</h1>
        <div class="user-info">
            <span>Καλώς ήρθες, <strong><?php echo esc_html($current_user->display_name); ?></strong></span>
            <br>
            <small>Τελευταία σύνδεση: <?php echo date('d/m/Y H:i'); ?></small>
            <br>
            <a href="<?php echo wp_logout_url(get_permalink()); ?>" class="logout-btn">🚪 Αποσύνδεση</a>
        </div>
    </header>
    
    <div class="dashboard-stats">
        <div class="stat-card">
            <h3><?php echo $total_flights; ?></h3>
            <p>📋 Συνολικές Πτήσεις</p>
        </div>
        <div class="stat-card">
            <h3><?php echo $this_year_flights; ?></h3>
            <p>🗓️ Πτήσεις <?php echo date('Y'); ?></p>
        </div>
        <div class="stat-card">
            <h3><?php echo date('M'); ?></h3>
            <p>📅 Τρέχων Μήνας</p>
        </div>
        <div class="stat-card">
            <h3>✅</h3>
            <p>🛡️ Active Status</p>
        </div>
    </div>
    
    <div class="dashboard-actions">
        <a href="<?php echo add_query_arg('action', 'new-flight', get_permalink()); ?>" class="action-btn primary">
            ➕ Νέα Καταχώρηση Πτήσης
        </a>
        <a href="<?php echo add_query_arg('action', 'my-flights', get_permalink()); ?>" class="action-btn secondary">
            📋 Οι Πτήσεις Μου
        </a>
        <a href="<?php echo add_query_arg('action', 'statistics', get_permalink()); ?>" class="action-btn secondary">
            📊 Στατιστικά
        </a>
        <a href="<?php echo add_query_arg('action', 'profile', get_permalink()); ?>" class="action-btn secondary">
            👤 Προφίλ
        </a>
    </div>
    
    <?php
    // Dynamic content based on action
    $action = isset($_GET['action']) ? $_GET['action'] : 'dashboard';
    
    switch($action) {
        case 'new-flight':
            echo '<div class="content-section">';
            echo '<h2>➕ Νέα Καταχώρηση Πτήσης</h2>';
            if(file_exists(plugin_dir_path(__FILE__) . 'frontend-form.php')) {
                include plugin_dir_path(__FILE__) . 'frontend-form.php';
            } else {
                echo '<div class="alert alert-info">
                    <h3>🔧 Φόρμα υπό κατασκευή</h3>
                    <p>Η φόρμα καταχώρησης πτήσης θα είναι σύντομα διαθέσιμη!</p>
                    <a href="' . remove_query_arg('action') . '" class="action-btn secondary">🔙 Επιστροφή</a>
                </div>';
            }
            echo '</div>';
            break;
            
        case 'my-flights':
            ?>
            <div class="user-flights-list">
                <h2>📋 Οι Πτήσεις Μου</h2>
                
                <?php if(empty($user_flights)): ?>
                    <div class="no-flights" style="text-align: center; padding: 40px; background: #f8f9fa; border-radius: 10px;">
                        <h3>🚁 Δεν υπάρχουν καταχωρημένες πτήσεις</h3>
                        <p>Ξεκινήστε καταχωρώντας την πρώτη σας πτήση!</p>
                        <a href="<?php echo add_query_arg('action', 'new-flight', get_permalink()); ?>" class="action-btn primary">
                            ➕ Πρώτη Πτήση
                        </a>
                    </div>
                <?php else: ?>
                    <div class="flights-table">
                        <table style="width: 100%; border-collapse: collapse; background: white; border-radius: 10px; overflow: hidden; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
                            <thead>
                                <tr style="background: linear-gradient(45deg, #667eea, #764ba2); color: white;">
                                    <th style="padding: 15px; text-align: left;">📅 Ημερομηνία</th>
                                    <th style="padding: 15px; text-align: left;">📍 Τοποθεσία</th>
                                    <th style="padding: 15px; text-align: left;">🚁 UAV</th>
                                    <th style="padding: 15px; text-align: left;">⏱️ Διάρκεια</th>
                                    <th style="padding: 15px; text-align: left;">🎯 Σκοπός</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($user_flights as $index => $flight): ?>
                                <tr style="background: <?php echo $index % 2 == 0 ? '#f8f9fa' : 'white'; ?>;">
                                    <td style="padding: 12px; border-bottom: 1px solid #eee;">
                                        <strong><?php echo date('d/m/Y', strtotime($flight->flight_date)); ?></strong>
                                        <br><small><?php echo date('H:i', strtotime($flight->flight_date)); ?></small>
                                    </td>
                                    <td style="padding: 12px; border-bottom: 1px solid #eee;">
                                        <?php echo esc_html($flight->location); ?>
                                    </td>
                                    <td style="padding: 12px; border-bottom: 1px solid #eee;">
                                        <?php echo esc_html($flight->uav_model); ?>
                                    </td>
                                    <td style="padding: 12px; border-bottom: 1px solid #eee;">
                                        <span style="background: #4CAF50; color: white; padding: 4px 8px; border-radius: 4px; font-size: 0.9em;">
                                            <?php echo esc_html($flight->duration); ?> λεπτά
                                        </span>
                                    </td>
                                    <td style="padding: 12px; border-bottom: 1px solid #eee;">
                                        <?php echo esc_html($flight->purpose); ?>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    
                    <div style="margin-top: 20px; text-align: center;">
                        <a href="<?php echo remove_query_arg('action'); ?>" class="action-btn secondary">🔙 Επιστροφή στο Dashboard</a>
                    </div>
                <?php endif; ?>
            </div>
            <?php
            break;
            
        case 'statistics':
            ?>
            <div class="statistics-section">
                <h2>📊 Στατιστικά Πτήσεων</h2>
                
                <div class="stats-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px; margin: 20px 0;">
                    <div class="stat-box" style="background: white; padding: 25px; border-radius: 10px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
                        <h3>📈 Στατιστικά Έτους <?php echo date('Y'); ?></h3>
                        <p><strong>Συνολικές πτήσεις:</strong> <?php echo $this_year_flights; ?></p>
                        <p><strong>Μέσος όρος/μήνα:</strong> <?php echo round($this_year_flights / date('n'), 1); ?></p>
                    </div>
                    
                    <div class="stat-box" style="background: white; padding: 25px; border-radius: 10px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
                        <h3>🏆 Συνολικά Δεδομένα</h3>
                        <p><strong>Όλες οι πτήσεις:</strong> <?php echo $total_flights; ?></p>
                        <p><strong>Ενεργός από:</strong> <?php echo date('Y', strtotime($current_user->user_registered)); ?></p>
                    </div>
                </div>
                
                <?php if(!empty($user_flights)): ?>
                <div class="recent-flights" style="background: white; padding: 25px; border-radius: 10px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); margin-top: 20px;">
                    <h3>🕒 Τελευταίες 5 Πτήσεις</h3>
                    <?php 
                    $recent_flights = array_slice($user_flights, 0, 5);
                    foreach($recent_flights as $flight): 
                    ?>
                        <div class="activity-item" style="padding: 15px; border-left: 4px solid #764ba2; margin: 10px 0; background: #f8f9fa; border-radius: 5px;">
                            <strong>📅 <?php echo date('d/m/Y H:i', strtotime($flight->flight_date)); ?></strong><br>
                            📍 <?php echo esc_html($flight->location); ?> | 
                            🚁 <?php echo esc_html($flight->uav_model); ?> | 
                            ⏱️ <?php echo esc_html($flight->duration); ?> λεπτά<br>
                            <small>🎯 <?php echo esc_html($flight->purpose); ?></small>
                        </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
                
                <div style="margin-top: 20px; text-align: center;">
                    <a href="<?php echo remove_query_arg('action'); ?>" class="action-btn secondary">🔙 Επιστροφή στο Dashboard</a>
                </div>
            </div>
            <?php
            break;
            
        case 'profile':
            ?>
            <div class="profile-section">
                <h2>👤 Προφίλ Χρήστη</h2>
                
                <div class="profile-info" style="background: white; padding: 30px; border-radius: 10px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                        <div>
                            <h3>📋 Βασικά Στοιχεία</h3>
                            <p><strong>Όνομα:</strong> <?php echo esc_html($current_user->display_name); ?></p>
                            <p><strong>Email:</strong> <?php echo esc_html($current_user->user_email); ?></p>
                            <p><strong>Username:</strong> <?php echo esc_html($current_user->user_login); ?></p>
                            <p><strong>Εγγραφή:</strong> <?php echo date('d/m/Y', strtotime($current_user->user_registered)); ?></p>
                        </div>
                        
                        <div>
                            <h3>🚁 Στοιχεία Πτήσεων</h3>
                            <p><strong>Συνολικές πτήσεις:</strong> <?php echo $total_flights; ?></p>
                            <p><strong>Πτήσεις <?php echo date('Y'); ?>:</strong> <?php echo $this_year_flights; ?></p>
                            <p><strong>Status:</strong> <span style="color: #4CAF50; font-weight: bold;">✅ Ενεργός</span></p>
                            <p><strong>Τελευταία δραστηριότητα:</strong> <?php echo date('d/m/Y H:i'); ?></p>
                        </div>
                    </div>
                    
                    <div style="margin-top: 30px; text-align: center; border-top: 1px solid #eee; padding-top: 20px;">
                        <a href="<?php echo admin_url('profile.php'); ?>" class="action-btn primary" target="_blank">
                            ⚙️ Επεξεργασία Προφίλ
                        </a>
                        <a href="<?php echo remove_query_arg('action'); ?>" class="action-btn secondary">
                            🔙 Επιστροφή στο Dashboard
                        </a>
                    </div>
                </div>
            </div>
            <?php
            break;
            
        default:
            ?>
            <div class="dashboard-overview">
                <h2>📊 Επισκόπηση Δραστηριότητας</h2>
                
                <div class="overview-grid" style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin: 20px 0;">
                    <div class="recent-activity" style="background: white; padding: 25px; border-radius: 10px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
                        <?php if(!empty($user_flights)): ?>
                            <h3>🕒 Πρόσφατες Πτήσεις</h3>
                            <?php 
                            $recent_flights = array_slice($user_flights, 0, 3);
                            foreach($recent_flights as $flight): 
                            ?>
                                <div class="activity-item" style="padding: 15px; border-left: 4px solid #764ba2; margin: 10px 0; background: #f8f9fa; border-radius: 5px;">
                                    <strong>📅 <?php echo date('d/m/Y', strtotime($flight->flight_date)); ?></strong><br>
                                    📍 <?php echo esc_html($flight->location); ?> 
                                    (⏱️ <?php echo esc_html($flight->duration); ?> λεπτά)
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <h3>🚁 Καλώς ήρθατε!</h3>
                            <p style="color: #666;">Δεν υπάρχουν πρόσφατες πτήσεις για εμφάνιση.</p>
                            <a href="<?php echo add_query_arg('action', 'new-flight', get_permalink()); ?>" class="action-btn primary" style="margin-top: 15px; display: inline-block;">
                                ➕ Καταχωρήστε την πρώτη σας πτήση
                            </a>
                        <?php endif; ?>
                    </div>
                    
                    <div class="quick-actions" style="background: white; padding: 25px; border-radius: 10px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
                        <h3>⚡ Γρήγορες Ενέργειες</h3>
                        <div style="display: flex; flex-direction: column; gap: 10px;">
                            <a href="<?php echo add_query_arg('action', 'new-flight', get_permalink()); ?>" class="quick-action-btn" style="padding: 12px; background: #4CAF50; color: white; text-decoration: none; border-radius: 5px; text-align: center;">
                                ➕ Νέα Πτήση
                            </a>
                            <a href="<?php echo add_query_arg('action', 'my-flights', get_permalink()); ?>" class="quick-action-btn" style="padding: 12px; background: #2196F3; color: white; text-decoration: none; border-radius: 5px; text-align: center;">
                                📋 Οι Πτήσεις Μου
                            </a>
                            <a href="<?php echo add_query_arg('action', 'statistics', get_permalink()); ?>" class="quick-action-btn" style="padding: 12px; background: #FF9800; color: white; text-decoration: none; border-radius: 5px; text-align: center;">
                                📊 Στατιστικά
                            </a>
                        </div>
                    </div>
                </div>
                
                <div class="welcome-message" style="background: linear-gradient(45deg, #667eea, #764ba2); color: white; padding: 30px; border-radius: 10px; text-align: center; margin-top: 20px;">
                    <h3>🚁 UAV Dashboard - Έτοιμο για χρήση!</h3>
                    <p>Καλώς ήρθατε στο σύστημα καταχώρησης πτήσεων. Διαχειριστείτε τις πτήσεις σας εύκολα και αποτελεσματικά.</p>
                </div>
            </div>
            <?php
    }
    ?>
</div>

<style>
/* Additional inline styles for better presentation */
.alert {
    padding: 20px;
    border-radius: 8px;
    margin: 20px 0;
}
.alert-info {
    background: #e3f2fd;
    border: 1px solid #2196F3;
    color: #1976D2;
}
.content-section {
    background: white;
    padding: 30px;
    border-radius: 10px;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    margin-top: 20px;
}
@media (max-width: 768px) {
    .overview-grid, .stats-grid {
        grid-template-columns: 1fr !important;
    }
    .dashboard-header {
        flex-direction: column;
        text-align: center;
    }
    .dashboard-actions {
        flex-direction: column;
    }
}
</style>