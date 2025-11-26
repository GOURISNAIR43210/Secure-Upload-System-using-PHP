# Secure PHP Upload System

A simple and secure PHP module for authenticated users to upload files (images, documents, etc.) — with built-in validation, sanitization, secure storage and metadata management.

## Features

- Validates uploaded files using a whitelist of allowed file extensions **and** MIME types / content-type detection. :contentReference[oaicite:0]{index=0}  
- Limits file size to a configured maximum to prevent oversized uploads. :contentReference[oaicite:1]{index=1}  
- Sanitizes original filenames and generates a *safe, unique filename* for storage (e.g. using timestamp + random string / hash) — avoids collisions and directory traversal / injection attacks. :contentReference[oaicite:2]{index=2}  
- Stores files in a secure directory (ideally outside the public web root, or in a folder with execution disabled) to prevent direct access or execution of uploaded files. :contentReference[oaicite:3]{index=3}  
- Records metadata (original filename, stored filename, uploader ID / username, upload timestamp, MIME type) in a database for audit, access control, and tracking.  
- Restricts upload functionality to authenticated users only.  
- Provides safe download/view functionality via server-side script (instead of exposing raw file URLs), allowing permission checks before serving files.

## Requirements

- PHP 7.x or above (or suitable PHP version)  
- A database (MySQL / Oracle / other) to store metadata (you can adapt according to your existing setup)  
- A secure directory for storing uploads — ideally outside the web-accessible root, or with server rules to prevent execution / direct access  
- Proper configuration in `php.ini` (e.g. `file_uploads = On`, appropriate `upload_max_filesize`, `post_max_size` values) :contentReference[oaicite:4]{index=4}

## Installation / Setup

1. Clone or copy the upload module (e.g. `upload.php`, `upload_handler.php`, `config.php`, `db_utils.php`, etc.) into your project.  
2. Configure database credentials and upload storage path in your configuration file.  
3. Ensure the upload folder exists and has correct permissions (writable by server, but not executable; avoid direct public access if possible).  
4. Use an HTML form for uploads, for example:

   ```html
   <form action="upload_handler.php" method="post" enctype="multipart/form-data">
     <input type="file" name="upload_file">
     <input type="submit" value="Upload">
   </form>
   ```

5. On form submission, `upload_handler.php` should:  
   - Verify user is authenticated.  
   - Validate file extension and MIME type / content.  
   - Check file size.  
   - Sanitize and rename file.  
   - Move the file securely to upload folder.  
   - Insert metadata into database.  
   - Return success/failure message to user.

## Example (upload_handler.php — simplified)

```php
<?php
session_start();
// Assume user authentication logic here
if (!isset($_SESSION['user_id'])) {
    die("Unauthorized");
}

if ($_FILES['upload_file']['error'] !== UPLOAD_ERR_OK) {
    die("Upload failed with error code " . $_FILES['upload_file']['error']);
}

// Configuration
$allowedExt = ['jpg','jpeg','png','pdf'];
$allowedMime = ['image/jpeg','image/png','application/pdf'];
$maxFileSize = 5 * 1024 * 1024; // 5 MB
$uploadDir = __DIR__ . '/../secure_uploads/';

// Validate size
if ($_FILES['upload_file']['size'] > $maxFileSize) {
    die("File too large");
}

// Validate MIME type
$finfo = finfo_open(FILEINFO_MIME_TYPE);
$mime = finfo_file($finfo, $_FILES['upload_file']['tmp_name']);
finfo_close($finfo);
if (!in_array($mime, $allowedMime)) {
    die("Invalid file type");
}

// Validate extension
$ext = strtolower(pathinfo($_FILES['upload_file']['name'], PATHINFO_EXTENSION));
if (!in_array($ext, $allowedExt)) {
    die("Invalid file extension");
}

// Sanitize & construct new filename
$baseName = preg_replace("/[^a-zA-Z0-9_-]/", "", pathinfo($_FILES['upload_file']['name'], PATHINFO_FILENAME));
$newName = time() . '_' . bin2hex(random_bytes(8)) . '_' . $baseName . '.' . $ext;

$destination = $uploadDir . $newName;

if (!move_uploaded_file($_FILES['upload_file']['tmp_name'], $destination)) {
    die("Failed to move uploaded file");
}

// TODO: Insert metadata into database: user_id, original_name, new_name, mime_type, file_size, upload_time

echo "Upload successful";
?>
```

## Security Considerations / Best Practices

- Use **MIME type checking + extension whitelist + validating file contents** (not only trusting `$_FILES["type"]`). :contentReference[oaicite:5]{index=5}  
- Store uploads outside the publicly accessible web root or restrict direct access (e.g. via server config or `.htaccess`). :contentReference[oaicite:6]{index=6}  
- Rename files to unpredictable unique names to prevent path-guessing or overwrites. :contentReference[oaicite:7]{index=7}  
- Limit file size (both via PHP config and in code). :contentReference[oaicite:8]{index=8}  
- When serving files for download/viewing, use a server-side script that verifies user permission before outputting the file — avoid exposing raw paths.  
- Log upload attempts (successful and failed), and track metadata for auditing and security monitoring.  

## How to Use in Your Project

- Integrate upload module in your existing user login + session system (only allow uploads for logged-in users).  
- Adapt the database metadata schema (depending on your DB engine).  
- Use this module for uploading user photos, documents, or other files.  
- On retrieval (view/download), add permission checks: e.g. ensure only the uploader (or allowed users) can access the file.  
- Regularly review uploaded files / logs — especially if allowing many users to upload.  

---

