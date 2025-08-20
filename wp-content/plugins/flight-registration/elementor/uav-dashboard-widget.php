<?php
if (!defined('ABSPATH')) {
    exit;
}

class UAV_Dashboard_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'uav-dashboard';
    }

    public function get_title() {
        return '🚁 UAV Dashboard';
    }

    public function get_icon() {
        return 'eicon-dashboard';
    }

    public function get_categories() {
        return ['general'];
    }

    protected function _register_controls() {
        
        $this->start_controls_section(
            'content_section',
            [
                'label' => '🚁 UAV Dashboard Settings',
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'dashboard_title',
            [
                'label' => 'Dashboard Title',
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => '🚁 UAV Flight Dashboard',
                'placeholder' => 'Enter dashboard title',
            ]
        );

        $this->add_control(
            'show_stats',
            [
                'label' => 'Show Statistics Cards',
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => 'Show',
                'label_off' => 'Hide',
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'show_actions',
            [
                'label' => 'Show Action Buttons',
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => 'Show',
                'label_off' => 'Hide', 
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->end_controls_section();

        // Style Section
        $this->start_controls_section(
            'style_section',
            [
                'label' => '🎨 Style Settings',
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'primary_color',
            [
                'label' => 'Primary Color',
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#764ba2',
            ]
        );

        $this->add_control(
            'secondary_color',
            [
                'label' => 'Secondary Color',
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#667eea',
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        
        echo '<div class="elementor-uav-dashboard">';
        
        if (!is_user_logged_in()) {
            ?>
            <div class="uav-login-prompt" style="
                text-align: center; 
                padding: 60px 30px; 
                background: linear-gradient(45deg, <?php echo $settings['secondary_color']; ?>, <?php echo $settings['primary_color']; ?>); 
                color: white; 
                border-radius: 15px; 
                margin: 30px 0;
                box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            ">
                <h2 style="margin: 0 0 20px 0; font-size: 2.5em;">🚁 UAV Dashboard</h2>
                <p style="font-size: 1.2em; margin-bottom: 30px;">Καλώς ήρθατε στο σύστημα διαχείρισης πτήσεων</p>
                <p style="margin-bottom: 30px;">Συνδεθείτε για πρόσβαση στο dashboard σας</p>
                <div>
                    <a href="<?php echo wp_login_url(get_permalink()); ?>" style="
                        background: white; 
                        color: <?php echo $settings['primary_color']; ?>; 
                        padding: 15px 30px; 
                        text-decoration: none; 
                        border-radius: 8px; 
                        margin: 10px; 
                        display: inline-block; 
                        font-weight: bold;
                        transition: transform 0.3s;
                    ">🔑 Σύνδεση</a>
                    <a href="<?php echo wp_registration_url(); ?>" style="
                        background: transparent; 
                        color: white; 
                        border: 2px solid white; 
                        padding: 13px 28px; 
                        text-decoration: none; 
                        border-radius: 8px; 
                        margin: 10px; 
                        display: inline-block; 
                        font-weight: bold;
                        transition: all 0.3s;
                    ">📝 Εγγραφή</a>
                </div>
            </div>
            <?php
        } else {
            // Show the dashboard
            $current_user = wp_get_current_user();
            $user_flights = get_user_flights($current_user->ID);
            $total_flights = count($user_flights);
            $this_year_flights = 0;
            
            foreach($user_flights as $flight) {
                if(date('Y', strtotime($flight->DATE)) == date('Y')) {
                    $this_year_flights++;
                }
            }
            ?>
            
            <div class="elementor-uav-content" style="--primary-color: <?php echo $settings['primary_color']; ?>; --secondary-color: <?php echo $settings['secondary_color']; ?>;">
                
                <!-- Header -->
                <header class="uav-header" style="
                    background: linear-gradient(45deg, var(--secondary-color), var(--primary-color));
                    color: white;
                    padding: 40px;
                    border-radius: 15px;
                    margin-bottom: 30px;
                    text-align: center;
                    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
                ">
                    <h1 style="margin: 0 0 15px 0; font-size: 2.5em;"><?php echo esc_html($settings['dashboard_title']); ?></h1>
                    <p style="margin: 0; font-size: 1.2em; opacity: 0.9;">
                        Καλώς ήρθες, <strong><?php echo esc_html($current_user->display_name); ?></strong>
                    </p>
                    <small style="opacity: 0.8;">Τελευταία σύνδεση: <?php echo date('d/m/Y H:i'); ?></small>
                </header>

                <?php if ($settings['show_stats'] === 'yes'): ?>
                <!-- Statistics -->
                <div class="uav-stats" style="
                    display: grid;
                    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
                    gap: 25px;
                    margin-bottom: 30px;
                ">
                    <div class="stat-card" style="
                        background: white;
                        padding: 30px;
                        border-radius: 15px;
                        text-align: center;
                        box-shadow: 0 8px 25px rgba(0,0,0,0.1);
                        border-left: 5px solid var(--primary-color);
                        transition: transform 0.3s;
                    ">
                        <h3 style="font-size: 3em; margin: 0 0 10px 0; color: var(--primary-color);"><?php echo $total_flights; ?></h3>
                        <p style="margin: 0; color: #666; font-weight: bold;">📋 Συνολικές Πτήσεις</p>
                    </div>
                    <div class="stat-card" style="
                        background: white;
                        padding: 30px;
                        border-radius: 15px;
                        text-align: center;
                        box-shadow: 0 8px 25px rgba(0,0,0,0.1);
                        border-left: 5px solid var(--secondary-color);
                        transition: transform 0.3s;
                    ">
                        <h3 style="font-size: 3em; margin: 0 0 10px 0; color: var(--secondary-color);"><?php echo $this_year_flights; ?></h3>
                        <p style="margin: 0; color: #666; font-weight: bold;">🗓️ Πτήσεις <?php echo date('Y'); ?></p>
                    </div>
                    <div class="stat-card" style="
                        background: white;
                        padding: 30px;
                        border-radius: 15px;
                        text-align: center;
                        box-shadow: 0 8px 25px rgba(0,0,0,0.1);
                        border-left: 5px solid #4CAF50;
                        transition: transform 0.3s;
                    ">
                        <h3 style="font-size: 3em; margin: 0 0 10px 0; color: #4CAF50;">✅</h3>
                        <p style="margin: 0; color: #666; font-weight: bold;">🛡️ Status: Active</p>
                    </div>
                </div>
                <?php endif; ?>

                <?php if ($settings['show_actions'] === 'yes'): ?>
                <!-- Actions -->
                <div class="uav-actions" style="
                    display: flex;
                    gap: 20px;
                    justify-content: center;
                    flex-wrap: wrap;
                    margin-bottom: 30px;
                ">
                    <a href="<?php echo add_query_arg('action', 'new-flight', get_permalink()); ?>" style="
                        background: linear-gradient(45deg, #4CAF50, #45a049);
                        color: white;
                        padding: 18px 35px;
                        text-decoration: none;
                        border-radius: 10px;
                        font-weight: bold;
                        font-size: 1.1em;
                        transition: all 0.3s;
                        box-shadow: 0 5px 15px rgba(76, 175, 80, 0.3);
                    ">➕ Νέα Πτήση</a>
                    
                    <a href="<?php echo add_query_arg('action', 'my-flights', get_permalink()); ?>" style="
                        background: linear-gradient(45deg, #2196F3, #1976D2);
                        color: white;
                        padding: 18px 35px;
                        text-decoration: none;
                        border-radius: 10px;
                        font-weight: bold;
                        font-size: 1.1em;
                        transition: all 0.3s;
                        box-shadow: 0 5px 15px rgba(33, 150, 243, 0.3);
                    ">📋 Οι Πτήσεις Μου</a>
                    
                    <a href="<?php echo add_query_arg('action', 'statistics', get_permalink()); ?>" style="
                        background: linear-gradient(45deg, #FF9800, #F57C00);
                        color: white;
                        padding: 18px 35px;
                        text-decoration: none;
                        border-radius: 10px;
                        font-weight: bold;
                        font-size: 1.1em;
                        transition: all 0.3s;
                        box-shadow: 0 5px 15px rgba(255, 152, 0, 0.3);
                    ">📊 Στατιστικά</a>
                </div>
                <?php endif; ?>

                <!-- Recent Activity -->
                <div class="uav-recent" style="
                    background: white;
                    padding: 40px;
                    border-radius: 15px;
                    box-shadow: 0 8px 25px rgba(0,0,0,0.1);
                ">
                    <h3 style="margin: 0 0 25px 0; color: var(--primary-color);">🕒 Πρόσφατη Δραστηριότητα</h3>
                    
                    <?php if (!empty($user_flights)): ?>
                        <?php $recent_flights = array_slice($user_flights, 0, 3); ?>
                        <?php foreach($recent_flights as $flight): ?>
                            <div style="
                                padding: 20px;
                                border-left: 4px solid var(--primary-color);
                                margin: 15px 0;
                                background: #f8f9fa;
                                border-radius: 8px;
                            ">
                                <strong>📅 <?php echo date('d/m/Y', strtotime($flight->DATE)); ?></strong><br>
                                📍 <?php echo esc_html($flight->ΓΕΝΙΚΗ_ΔΝΣΗ); ?> | 
                                🚁 <?php echo esc_html($flight->UAV_TYPE); ?> | 
                                ⏱️ <?php echo esc_html($flight->FLIGHT_TIME); ?> λεπτά<br>
                                <small>🎯 <?php echo esc_html($flight->FLIGHT_PURPOSE); ?></small>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div style="text-align: center; padding: 40px; color: #666;">
                            <h4>🚁 Καλώς ήρθατε!</h4>
                            <p>Δεν υπάρχουν καταχωρημένες πτήσεις ακόμα.</p>
                            <a href="<?php echo add_query_arg('action', 'new-flight', get_permalink()); ?>" style="
                                background: var(--primary-color);
                                color: white;
                                padding: 15px 30px;
                                text-decoration: none;
                                border-radius: 8px;
                                display: inline-block;
                                margin-top: 15px;
                            ">🚀 Καταχωρήστε την πρώτη σας πτήση</a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <style>
            .stat-card:hover {
                transform: translateY(-5px);
            }
            .uav-actions a:hover {
                transform: translateY(-3px);
                box-shadow: 0 8px 25px rgba(0,0,0,0.2);
            }
            @media (max-width: 768px) {
                .uav-actions {
                    flex-direction: column;
                    align-items: center;
                }
                .uav-header {
                    padding: 30px 20px;
                }
            }
            </style>
            <?php
        }
        
        echo '</div>';
    }
}