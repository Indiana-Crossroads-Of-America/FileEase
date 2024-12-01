# RM Solutions: I:COA FileEase

## About FileEase

This **open-source solution**, built with PHP and NGINX, provides an intuitive GUI for file uploads combined with basic NGINX traffic rewrites for flagged/banned images. When paired with Cloudflare's Proxy Firewall, it offers robust logging functionalities, including:

- Capturing `CF-RealIP` and `CF-RayID` for detailed logs.
- Logging uploaded file names, renaming files, and tracking new file names with associated RealIP.
- Handling flagged images through compliance mechanisms.

This tool is designed for advanced users with expertise in PHP, NetStack, and server infrastructure. ⚠️ **Important**: It cannot operate on shared hosting environments due to server-level configuration requirements.

---

## Key Features

1. **Session Tracking**  
   - Enforces cooldown periods between uploads.  

2. **Configurable Restrictions**  
   - Supports a default maximum file size of `70MB`.
   - Restricts specific file extensions to prevent harmful script execution.  

3. **Cloudflare Integration**  
   - Captures user IP addresses and Ray IDs using `CF-Connecting-IP` and `CF-Ray`.

4. **File Storage**  
   - Saves files with unique names in a configurable directory.
   - Generates unique URLs for file access.

5. **Logging**  
   - Logs file activities, stored in the `/srv/mount/logs` directory by default.

This tool is provided **as-is**, with no official support. Community contributions are welcome to enhance its functionality over time.
