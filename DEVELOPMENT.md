# Development Setup Guide

## File Upload Configuration

This application supports file uploads up to **15MB**. To ensure this works on your local development environment, you need to configure your PHP settings.

### Option 1: Update PHP Configuration (Recommended)

#### For Herd Users:

1. **Find your PHP configuration file:**

    ```bash
    # Usually located at:
    C:\Users\[username]\.config\herd\bin\php84\php.ini
    # or
    C:\Users\[username]\.config\herd\bin\php82\php.ini
    ```

2. **Update these settings:**

    ```ini
    upload_max_filesize = 16M
    post_max_size = 20M
    max_execution_time = 300
    max_input_time = 300
    memory_limit = 256M
    ```

3. **Restart Herd** after making changes

#### For Other Development Environments:

- **XAMPP**: Edit `xampp/php/php.ini`
- **WAMP**: Edit `wamp/bin/php/php8.x.x/php.ini`
- **MAMP**: Edit `MAMP/bin/php/php8.x.x/conf/php.ini`
- **Docker**: Update your Dockerfile or docker-compose.yml

### Option 2: Check Current Settings

To verify your current PHP settings, you can:

1. **Check via CLI:**

    ```bash
    php -i | grep -E "upload_max_filesize|post_max_size"
    ```

2. **Check via web:**
    - Create a temporary `phpinfo.php` file in your public directory
    - Access it via your local URL
    - Look for the upload settings

### Required Settings

| Setting               | Value | Purpose                                                |
| --------------------- | ----- | ------------------------------------------------------ |
| `upload_max_filesize` | 16M   | Maximum file size for uploads                          |
| `post_max_size`       | 20M   | Maximum POST data size (must be > upload_max_filesize) |
| `max_execution_time`  | 300   | Maximum script execution time                          |
| `max_input_time`      | 300   | Maximum time to parse input data                       |
| `memory_limit`        | 256M  | Maximum memory usage                                   |

### Troubleshooting

If file uploads still fail after updating PHP settings:

1. **Restart your web server** (Apache/Nginx)
2. **Clear browser cache**
3. **Check browser console** for JavaScript errors
4. **Check Laravel logs** at `storage/logs/laravel.log`

### Notes

- The `.htaccess` file includes web server level configurations
- Laravel validation rules are set to `max:15360` (15MB)
- Client-side validation shows 15MB limit to users
