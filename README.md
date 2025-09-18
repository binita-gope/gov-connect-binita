# GovConnect

GovConnect is a **PHP & MySQL-based web application** designed to provide a platform for government-related services.  
This repository includes all necessary PHP files and a SQL file to set up the database.

---

## 📌 Requirements

Before running this project, make sure you have the following installed:

- [XAMPP](https://www.apachefriends.org/) (with PHP 8.x and Apache)
- MySQL 8.0 (bundled with XAMPP or standalone installation)
- A web browser (Chrome, Firefox, Edge, etc.)
- Git (optional, for cloning the repo)

---

## ⚙️ Installation Guide

Follow these steps to install and run the project on your system:


1. Download or Clone the Project
   - Clone using Git:
     git clone https://github.com/your-username/govconnect.git
   - OR download as ZIP and extract.
   - Move the folder into your XAMPP htdocs directory:
     C:\xampp\htdocs\govconnect

2. Start Apache and MySQL
   - Open XAMPP Control Panel
   - Start Apache
   - Start MySQL
   - Both should show green indicators

3. Import the Database
   - Open browser and go to: http://localhost/phpmyadmin
   - Click Databases → create a database named: govconnect
   - Select govconnect
   - Go to Import → Choose File → select govconnect.sql
   - Click Go to import

4. Configure Database Connection
   - Open the file: govconnect/config.php
   - Update with your MySQL credentials:

     <?php
     $host = "localhost";
     $user = "root";        // Default XAMPP username
     $pass = "";            // Default XAMPP password is empty
     $db   = "govconnect";  // Database name

     $conn = new mysqli($host, $user, $pass, $db);

     if ($conn->connect_error) {
         die("Connection failed: " . $conn->connect_error);
     }
     ?>

5. Run the Project
   - Open browser and visit: http://localhost/govconnect
---

## 📂 Project Structure
govconnect/
│
├── config.php         # Database connection
├── index.php          # Homepage
├── dashboard.php      # Dashboard (after login)
├── login.php          # Login page
├── register.php       # Registration page
├── logout.php         # Logout functionality
├── assets/            # CSS, JS, Images
│   ├── css/
│   ├── js/
│   └── images/
├── govconnect.sql     # Database file
└── README.md          # Documentation

