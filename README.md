# 🚁 UAV Flight Registration System

**Professional Flight Management Dashboard for UAV Operations**

## 📋 Overview

Advanced WordPress plugin for managing and tracking UAV (Unmanned Aerial Vehicle) flights. Designed specifically for professional aviation operations and compliance requirements.

## ✨ Features

### 🎯 Core Functionality
- **Flight Registration** - Complete flight logging system
- **Real-time Dashboard** - Live statistics and monitoring
- **Data Export** - Excel/CSV export capabilities
- **User Management** - Role-based access control
- **Database Integration** - Secure MySQL storage

### 🌟 Advanced Features
- **Progressive Web App (PWA)** - Mobile & desktop app experience
- **Offline Capabilities** - Work without internet connection
- **Push Notifications** - Real-time alerts and updates
- **Responsive Design** - Works on all devices
- **Greek Language Support** - Full localization

## 🛠️ Technology Stack

- **Backend:** PHP 8.0+, WordPress 6.0+
- **Database:** MySQL 8.0+
- **Frontend:** HTML5, CSS3, JavaScript ES6+
- **PWA:** Service Workers, Web App Manifest
- **Styling:** Custom CSS with modern design
- **Icons:** Dashicons, Custom UAV icons

## 📦 Installation

### Requirements
- WordPress 6.0+
- PHP 8.0+
- MySQL 8.0+
- XAMPP/WAMP (for local development)

### Steps
1. Download the plugin files
2. Upload to `/wp-content/plugins/flight-registration/`
3. Activate the plugin in WordPress admin
4. Configure database settings
5. Access UAV Dashboard

## 🚀 Usage

### Dashboard Access
- **URL:** `/wp-admin/admin.php?page=flight-dashboard`
- **Mobile App:** Install PWA from browser
- **Admin Panel:** WordPress Admin → UAV Dashboard

### Flight Registration
1. Navigate to "New Flight Registration"
2. Fill in flight details
3. Submit and view in dashboard
4. Export data as needed

## 📱 PWA Installation

The system works as a Progressive Web App:

1. **Mobile:** Open in browser → "Add to Home Screen"
2. **Desktop:** Chrome → Address bar → Install icon
3. **iOS:** Safari → Share → "Add to Home Screen"

## 🗂️ File Structure

```
flight-registration/
├── assets/
│   ├── css/
│   │   ├── dashboard-style.css
│   │   └── admin.css
│   ├── js/
│   │   ├── script.js
│   │   └── sw.js (Service Worker)
│   ├── icons/ (PWA icons)
│   └── manifest.json
├── includes/
│   ├── database.php
│   └── form-handler.php
├── templates/
│   ├── dashboard-index.php
│   ├── admin-page.php
│   └── flights-list.php
└── flight-registration.php (Main plugin file)
```

## 🔧 Configuration

### Database Table
The plugin creates: `wp_καταχώρηση_Πτήσεων`

### Required Capabilities
- `manage_options` for admin access
- Custom roles can be configured

## 🎨 Screenshots

![Dashboard](screenshots/dashboard.png)
![Flight Form](screenshots/flight-form.png)
![Mobile App](screenshots/mobile-app.png)

## 🤝 Contributing

1. Fork the repository
2. Create feature branch: `git checkout -b feature-name`
3. Commit changes: `git commit -m 'Add feature'`
4. Push to branch: `git push origin feature-name`
5. Submit Pull Request

## 📄 License

This project is licensed under the GPL v2 or later - see the [LICENSE](LICENSE) file for details.

## 🆘 Support

- **Issues:** [GitHub Issues](https://github.com/yourusername/uav-flight-registration/issues)
- **Documentation:** [Wiki](https://github.com/yourusername/uav-flight-registration/wiki)
- **Email:** support@yoursite.com

## 🏆 Credits

Developed for professional UAV operations and aviation compliance requirements.

---

**⭐ Star this repo if you find it useful!**
