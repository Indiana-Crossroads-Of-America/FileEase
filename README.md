# RM Solutions: I:COA FileEase

## About FileEase
  

This **open-source solution**, built with PHP and NGINX, provides an intuitive GUI for file uploads using strictly PHP & HTML combined with a basic NGINX-Service Level Traffic Rewrite for Flagged/Banned Images. When paired with Cloudflare's Proxy Firewall, it ensures robust logging functionalities including:

- Capturing `CF-RealIP` and `CF-RayID` for precise, detailed logs.
- Designed to be open-source, customizable, and scalable for in-house deployment based on Use Case.
- Logging uploaded file names, renaming files (via `index.php`), and tracking the new file name along with the associated RealIP in logs (Index.php).
- FAL; which collects the RealIP of users trying to access Flagged Images.

##### **Note**: This tool is designed for advanced users with expertise in PHP, NetStack, and server infrastructure. ⚠️**Important**: It **cannot** operate on shared hosting environments ⚠️. This limitation arises from compliance mechanisms that require server-level configurations, such as request interception and routing, which are typically restricted or unsupported by shared hosting providers due to their structural and security constraints.
---

## Key Features and Requirements

### File Sharing Modes
- **Permanent and Temporary File Sharing**:
  - Temporary sharing requires:
    - Scheduled tasks (e.g., cron jobs) to run cleanup scripts like `php clean.php` (Standard PHP Script for 1W/7D).

### Mandatory Configuration
- **NGINX Configuration**:
  - Update the `block.nginx` file with your Certificate PEM and Private Key.
  - Deploy these configurations to your NGINX ecosystem.
  - Avoid using CertBot; instead, leverage Cloudflare's free Strict SSL to enable features like `RayID` and `RealIP` via their Proxy Infrastructure.
 
- **PHP Script Configuration**:  
  - `serve.php` manages everything from the JSON file name and its location to how it processes and intercepts "Processed Compliance Files".
  - Set the file directory on `L3:$directory` to the location of your JSON file.  
  - Set the file name on `L4:$tosFile` to correspond to your JSON file name.  

```php
php L3:$directory = '/srv/mount/files/';
php L4:$tosFile = __DIR__ . '/tos_violations.json'; // Ensure this matches your setup
```
---

## Features

1. **Session Tracking**  
   - Tracks user activity and enforces cooldown periods between uploads.

2. **Configurable Restrictions**  
   - Default maximum file size is `70MB` (Cloudflare supports up to `100MB`).
   - Restricts specific file extensions to prevent execution of harmful scripts.

3. **Cloudflare Integration (Optional)**  
   - Logs user IP addresses and Cloudflare Ray IDs:
     - **User IP**: Captured via `CF-Connecting-IP` or falls back to the standard remote address.
     - **Ray ID**: Captured from the `CF-Ray` header if available.

4. **Upload Cooldown**  
   - Enforces a default cooldown of 1 minute between uploads.

5. **File Storage**  
   - Saves files in a configurable directory with unique names to avoid overwrites.
   - Generates unique URLs for file access.
     
6. **Logging**  
By default, logs are uploaded to the `/srv/mount/logs` directory, using an HTML File. You can modify this behavior in the `index.php` file by updating the following lines:  

```php
// Set target directories
$uploadDir = '/srv/mount/files/';
$logDir = '/srv/mount/logs/';
```

##### *Note: Ensure each directory has the appropriate Server/WWW write permissions. Although the client does not require write access, the WebServer must have permissions to create and write HTML files. It is highly recommended to secure access control using HTTP Authentication (HTTPDAuth) or Zero Trust Network Access (ZTNA).*
---

## Configuration Guide

### Adjust Blocked File Types
Edit the list of restricted file extensions to suit your needs. For example:
- Default blocked extensions: `bash`, `sh`, `shell`, `html`, `php`.

### Set Maximum File Size
- Default size: `70MB`. Cloudflare’s free plan limits uploads to `100MB`.

### Modify Upload Cooldown
- Default cooldown: 1 minute. You can adjust this to fit your requirements.

### Customize Storage Directory
- Specify the upload directory:
  - Ensure the directory exists and is writable by the server.

### Enable Cloudflare Integration
- Configure the headers for user IP and Ray ID logging:
  - Use `CF-Connecting-IP` for IP address.
  - Use `CF-Ray` for the Ray ID if available.

---


#### __This tool is provided **as-is**, with no official support. Community contributions are encouraged to improve the project over time. Ensure careful deployment and thorough validation of configurations to secure your environment__.
