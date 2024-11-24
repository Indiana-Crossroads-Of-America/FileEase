# RM Solutions; I:COA Sharepoint

# What is SharePoint?

SharePoint is evolving into an **open-source solution**, powered by PHP and Nginx. It features a user-friendly GUI for front-end file uploads, supported by a basic compliance system. The platform optionally leverages Cloudflare's Proxy Firewall to provide enhanced functionality, including exports like `CF-RealIP` and `CF-RayID`. While optional, these features significantly bolster security and improve compliance and logging capabilities over time.

### File Upload Tool Documentation

This script is a **configurable file upload system** with restrictions and safeguards to ensure secure and efficient usage.

---

### Features:

1. **Session Tracking**  
   - Tracks user activity to enforce cooldown periods between uploads.

2. **Configurable Restrictions**  
   - **Maximum File Size**: Default is `70MB` (100MB for Cloudflare). 
   - **Blocked File Types**: Restricts certain file extensions to prevent abuse or execution of harmful scripts.

3. **Cloudflare Integration (Optional)**  
   - Captures the user's IP and Cloudflare Ray ID for enhanced logging.  
     - **User IP**: Obtained using the `CF-Connecting-IP` header or falls back to the standard remote address.  
     - **Ray ID**: Captured from the `CF-Ray` header if available.

4. **Upload Cooldown**  
   - Enforces a cooldown between uploads (default: 1 minute).

5. **File Storage**  
   - Files are saved in a configurable directory with unique names to avoid overwriting.  
   - A unique URL for accessing the uploaded files is generated dynamically.

---

### Configuration Guide:

1. **Change the Blocked File Types**  
   - Edit the `$restrictedExtensions` array in the script:  
     ```php
     $restrictedExtensions = ['bash', 'sh', 'shell', 'html', 'php'];
     ```
   - Add or remove file extensions based on your requirements.

2. **Set the Maximum File Size**
⚠️*Be Careful: Anything over 100MB+ on CloudFlares Free Plan will throw a "Too Large Error"*
   - Modify `$maxFileSize` (in bytes):  
     ```php
     $maxFileSize = 70 * 1024 * 1024; // Default: 70MB
     ```

4. **Adjust the Upload Cooldown**  
   - Update `$uploadCooldown` (in seconds):  
     ```php
     $uploadCooldown = 60; // Default: 1 minute
     ```

5. **Customize the Storage Directory**  
   - Change the `$uploadDir` variable to your desired storage location:  
     ```php
     $uploadDir = '/path/to/your/upload/directory/';
     ```
   - Ensure the directory exists and is writable by the server.

6. **Cloudflare Variables**  
   - Configure the IP and Ray ID headers if needed:  
     ```php
     $userIP = $_SERVER['HTTP_CF_CONNECTING_IP'] ?? $_SERVER['REMOTE_ADDR'];
     $rayID = $_SERVER['HTTP_CF_RAY'] ?? 'Unavailable';
     ```

7. **File Access URL**  
   - Adjust the URL used to serve uploaded files:  
     ```php
     $fileUrl = "https://yourdomain.com/serve/" . $uniqueFilename;
     ```

---

### General Warning:
- This tool can be configured for **Permanent** or **Temporary File Sharing** based on your infrastructure and the capacity of your storage arrays.  
- For **Temporary Sharing**, you must implement your own scheduled tasks (e.g., cron jobs) to execute cleanup scripts like `php remove.php`.  
- You must also esign and define the retention policy to determine which files to delete or retain within your desired timeframe.


**It falls on you to configure this to match your Linux Ecosystem and/or infrastructure. There is **absolutely** no authentication system, and out of the box, this system allows unauthenticated, raw uploads.








**Support is not provided and the product is as-is; but empowered by community programmers**


