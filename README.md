# RM Solutions: I:COA SharePoint

## Deployment Guide

This tool is highly configurable and requires setup tailored to your infrastructure. Proper configuration ensures secure and efficient operation.

**Note**: This tool is designed for advanced users with expertise in PHP, NetStack, and server infrastructure. ⚠️**Important**: It **cannot** operate on shared hosting environments ⚠️. This limitation arises from compliance mechanisms that require server-level configurations, such as request interception and routing, which are typically restricted or unsupported by shared hosting providers due to their structural and security constraints.
---

## Key Features and Requirements

### File Sharing Modes
- **Permanent and Temporary File Sharing**:
  - Temporary sharing requires:
    - Scheduled tasks (e.g., cron jobs) to run cleanup scripts like `php clean.php`.
    - A defined retention policy to manage file cleanup.

### Mandatory Configuration
- **NGINX Configuration**:
  - Update the `block.nginx` file with your Certificate PEM and Private Key.
  - Deploy these configurations to your NGINX ecosystem.
  - Avoid CertBot; instead, use Cloudflare's Free Strict SSL for custom SSL setups.

- **PHP Script Configuration**:
  - Customize `serve.php` to align with your Linux environment and infrastructure.
  - The script reads a JSON file (`tos_violations.json`) to enforce compliance by managing restricted files.

  Example configuration:
  - Set the file directory to `/srv/mount/files/`.
  - Use `tos_violations.json` for compliance handling.

---

## Warnings and Disclaimers

### Authentication
- Out of the box, this tool has **no authentication system**. File uploads are unauthenticated by default.
- You must implement authentication or access control mechanisms for your environment.

### Custom Configuration
- Misconfigurations in file paths, security settings, or dependencies may lead to vulnerabilities or unauthorized access.
- Validate all settings to ensure a secure deployment.

---

## About SharePoint

This tool is an **open-source solution** powered by PHP and NGINX. It provides a user-friendly GUI for file uploads and supports compliance features. When combined with Cloudflare's Proxy Firewall, it enables advanced logging and compliance capabilities, such as:
- Capturing `CF-RealIP` and `CF-RayID` for enhanced logging.
- Supporting stricter security measures over time.

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

## Final Notes

This tool is provided **as-is**, with no official support. Community contributions are encouraged to improve the project over time. Ensure careful deployment and thorough validation of configurations to secure your environment.
