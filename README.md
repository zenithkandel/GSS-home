# LifeLine - Emergency Response System (Backend & Portal)

> **Created by Team Spark**
> A resilient, offline-first emergency response network for remote areas.

## 📖 Overview

**LifeLine** is the server-side infrastructure for a LoRa-based mesh network designed to save lives in rural Nepal. In areas without cellular coverage, our hardware nodes (Spark Nodes) transmit SOS signals via LoRa to a central gateway. This repository contains the **Cloud Backend** and **Command & Control Portal** that receives these signals, processes them, and coordinates rescue efforts.

### The Ecosystem

1.  **Spark Node (Hardware)**: ESP32 + LoRa device that predicts health risks and sends SOS signals.
2.  **Mesh Network**: Devices hop signals to reach a gateway.
3.  **LifeLine Server (This Repo)**:
    - **API**: Receives encrypted/encoded data from the Gateway.
    - **Database**: Stores incidents, devices, and responders.
    - **Portal**: A Progressive Web App (PWA) for authorities to dispatch help.

---

## ✨ Key Features

- **📡 IoT Gateway API**: RESTful endpoints to receive data from LoRa gateways.
- **⚡ Real-time Alerts**:
  - **Firebase Cloud Messaging (FCM)**: Instant push notifications to admins.
  - **Email Automation**: Automatic alerts to registered responders.
- **🗺️ Bandwidth Optimization**: Uses an "Index Mapping" system to decode integer codes (e.g., `1` -> "Landslide at Namche") to minimize LoRa packet size.
- **🏥 Resource Management**: Track responders (police, medics, heli-rescue) and their status.
- **📱 Admin Portal**:
  - Live Dashboard of active emergencies.
  - Interactive Map (integration ready).
  - Device Health Monitoring (Battery, RSSI, Last Ping).
- **🔒 Secure Authentication**: Role-based access control for portal users.

---

## 🛠️ Technology Stack

- **Backend**: PHP 8.0+ (No framework, vanilla PHP for performance on low-resource servers)
- **Database**: MySQL / MariaDB
- **Frontend**: HTML5, CSS3, Vanilla JavaScript (PWA enabled)
- **Dependencies**:
  - `PHPMailer`: For email alerts.
  - `Google Auth Library`: For Firebase FCM authentication.
- **Infrastructure**: Apache/Nginx web server.

---

## 📂 Project Structure

```text
lifeline/
├── API/                 # REST API Endpoints
│   ├── auth/            # Login/Logout logic
│   ├── Create/          # POST endpoints (Receive SOS, add devices)
│   ├── Read/            # GET endpoints (Fetch data for portal)
│   ├── Update/          # PUT endpoints (Update status)
│   ├── Delete/          # DELETE endpoints
│   ├── email_helper.php # Mailer Logic
│   └── fcm_helper.php   # Push Notif Logic
├── portal/              # Admin Dashboard (Frontend)
│   ├── dashboard.php    # Main view
│   ├── devices.php      # Node management
│   ├── messages.php     # SOS log
│   └── ...
├── config/              # Configuration files
├── database.php         # DB Connection wrapper
├── firebase.json        # FCM Service Account Credentials
└── composer.json        # PHP Dependencies
```

---

## 🗄️ Database Structure

The system uses a relational database (MySQL/MariaDB) with the following schema:

```sql
-- Devices in the mesh network
CREATE TABLE `devices` (
  `DID` int(10) NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `device_name` varchar(100) DEFAULT NULL,
  `LID` int(10) NOT NULL COMMENT 'Location ID mapped from indexes',
  `status` enum('active','inactive','maintenance') DEFAULT 'active',
  `last_ping` datetime DEFAULT NULL
);

-- Emergency messages received from devices
CREATE TABLE `messages` (
  `MID` int(10) NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `DID` int(10) NOT NULL, -- Foreign Key -> devices.DID
  `RSSI` int(10) DEFAULT NULL,
  `message_code` int(10) NOT NULL COMMENT 'Mapped from indexes',
  `timestamp` datetime DEFAULT current_timestamp(),
  `status` text NOT NULL DEFAULT 'active'
);

-- Index mappings (Integer Code -> String Value) for Bandwidth Optimization
CREATE TABLE `indexes` (
  `IID` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `type` enum('location','message','help') NOT NULL,
  `mapping` longtext COMMENT 'JSON object mapping codes to meanings',
  `description` text DEFAULT NULL,
  `updated_at` datetime DEFAULT current_timestamp()
);

-- Registered Responders/Help Resources
CREATE TABLE `helps` (
  `HID` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `name` varchar(150) NOT NULL,
  `contact` varchar(50) NOT NULL,
  `eta` varchar(50) DEFAULT NULL,
  `status` enum('available','dispatched','busy') DEFAULT 'available',
  `location` varchar(200) DEFAULT NULL,
  `for_messages` text NOT NULL COMMENT 'JSON array of message codes this help handles'
);

-- Portal Users
CREATE TABLE `user` (
  `UID` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL UNIQUE,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','operator') DEFAULT 'operator',
  `last_login` datetime DEFAULT NULL
);

-- Email Alert Recipients
CREATE TABLE `emails` (
  `sn` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `email` varchar(255) NOT NULL UNIQUE
);

-- Firebase Cloud Messaging Tokens
CREATE TABLE `fcm_tokens` (
  `id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `token` varchar(512) NOT NULL UNIQUE,
  `user_agent` text DEFAULT NULL,
  `active` tinyint(1) DEFAULT 1
);
```

---

## 🚀 Installation & Setup

### Prerequisites

- PHP 8.0 or higher
- MySQL Database
- Composer (PHP Dependency Manager)

### 1. Database Setup

1.  Create a MySQL database named `lifeline` (or your preferred name).
2.  Import the schema from `../lifeline.sql` (found in the root project folder).
    ```sql
    source /path/to/lifeline.sql;
    ```

### 2. Configuration

1.  Edit `lifeline/database.php`:
    ```php
    define('DB_HOST', 'localhost');
    define('DB_NAME', 'lifeline');
    define('DB_USER', 'your_user');
    define('DB_PASS', 'your_password');
    ```

### 3. Dependencies

Install the required PHP libraries via Composer:

```bash
cd lifeline
composer install
```

### 4. Firebase Setup (Optional for Notifications)

1.  Place your Firebase Service Account JSON file in `lifeline/firebase.json`.
2.  Ensure it contains the `project_id` and private key.

---

## 📖 API Documentation

The system exposes a comprehensive REST API.
👉 **[View Full API Documentation](API/README.md)**

**Quick Example:**
To report an emergency (simulating a gateway):

```bash
POST /API/Create/message.php
Content-Type: application/json

{
    "DID": 1,           // Device ID
    "message_code": 5,  // e.g., "Severe Weather"
    "RSSI": -85         // Signal Strength
}
```

---

## 🌟 Managing the System

### The Portal

Navigate to `/lifeline/portal/` in your browser.

1.  **Dashboard**: See active SOS calls.
2.  **Mapping**: Manage the "Index Maps".
    - _Why?_ LoRa can't send "Kathmandu" efficiently. It sends `16`.
    - In the portal, you map `Type: Location, Code: 16 -> Value: Kathmandu`.
3.  **Responders**: Add local contacts (Sherpas, Police posts) so they can be dispatched.

---

## 🤝 Contributing

**Team Spark**

- Sakshyam Bastakoti
- Sakshyam Upadhaya
- Zenith Kandel
