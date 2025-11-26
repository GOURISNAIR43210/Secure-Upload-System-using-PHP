# Secure File Upload System (PHP + MySQL)

This project is a secure file upload module built using PHP and MySQL.  
It includes user authentication, safe file handling, and a logging system to track all uploads, including rejected or suspicious files.  
The goal of this project is to demonstrate how to build a safe upload mechanism that prevents malicious activities such as script uploads and file injection.

---

## 🚀 Features

- User registration and login system  
- Secure file uploading with:
  - File type validation (`jpg`, `png`, `pdf`, `docx`)
  - File size limit (max 5MB)
  - Filename sanitization to remove unsafe characters
  - Unique filename generation to avoid overwrites
- Server-side MIME type checking
- Upload logs containing:
  - User ID
  - IP address
  - Filename
  - Status (success / rejected)
  - Reason for rejection
- MySQL database with 3 tables:
  - `user`
  - `uploads`
  - `logs`

---

## 📦 Requirements

- PHP 8+  
- XAMPP or any Apache + MySQL server  
- MySQL/MariaDB  
- Enabled PHP extensions:
  - `mysqli`
  - `fileinfo`
  - `session`


## 🛠️ Project Setup

Follow the steps below to set up and run the Secure File Upload System on your local machine.

### 1️⃣ Install Requirements
Make sure you have the following installed:

- XAMPP (Apache + MySQL)
- PHP 8+ (already included in XAMPP)
- MySQL/MariaDB
- A web browser

Enable the PHP extensions:
- `mysqli`
- `fileinfo`
- `session`

These are usually enabled by default in XAMPP.

---

### 2️⃣ Clone or Download the Project
Place the project folder inside: C:\xampp\htdocs\
For example, if your project folder is named 'secure-upload', it should be: C:\xampp\htdocs\secure-upload


---

## 4️⃣ Configure the Database
1. Open **phpMyAdmin** in your browser:  


2. Create a new database, e.g., `secure_upload_db`.  
3. Import the SQL file included in the project (if any) to create the necessary tables.  
4. Update your database configuration in `config.php` (or similar) with your database credentials:




---

##  5️⃣ **Run the Project**
  
   - Go to your browser and type the following URLs:  
     - **Register Page:** [http://localhost/secure_upload/register.php]  
     Now you can other pages by registering in this page and by loging in using the same credential that is registed.You can Upload the file with the constrains provided and atlast logout.







