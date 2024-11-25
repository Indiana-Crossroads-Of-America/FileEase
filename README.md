# RM Solutions: I:COA SharePoint

## About SharePoint  

This tool is an **open-source solution** powered by PHP and NGINX, offering an intuitive GUI for file uploads along with advanced compliance features. When integrated with Cloudflare's Proxy Firewall, it provides robust logging and compliance capabilities, including:  
- Capturing `CF-RealIP` and `CF-RayID` for precise and detailed logs.  
- Built to be open-source, highly customizable, and scalable for in-house deployment, with the potential to achieve enterprise-level functionality—features that remain partially developed in the current version.


#### **Note**: This tool is designed for advanced users with expertise in PHP, NetStack, and server infrastructure. ⚠️**Important**: It **cannot** operate on shared hosting environments ⚠️. This limitation arises from compliance mechanisms that require server-level configurations, such as request interception and routing, which are typically restricted or unsupported by shared hosting providers due to their structural and security constraints.
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


## About SharePoint

This tool is an **open-source solution** powered by PHP and NGINX. It provides a user-friendly GUI for file uploads and supports compliance features. When combined with Cloudflare's Proxy Firewall, it enables advanced logging and compliance capabilities, such as:
- Capturing `CF-RealIP` and `CF-RayID` for enhanced logging.
- Supporting stricter security measures over time.
- This tool is provided **as-is**, with no official support. Community contributions are encouraged to improve the project over time. Ensure careful deployment and thorough validation of configurations to secure your environment.
