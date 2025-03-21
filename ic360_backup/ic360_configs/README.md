# IC360 Moodle Configurations

## Directory Structure
- nginx/: Nginx web server configurations
- php/: PHP and PHP-FPM configurations
- supervisor/: Process supervisor configurations
- scripts/: Container management scripts
- custom/: Custom Moodle modules and plugins

## Configuration Files
### Nginx
- nginx-config.conf: Main Nginx configuration
- default.conf: Default server block configuration

### PHP
- php-config.ini: PHP configuration for Moodle

### Supervisor
- supervisor.conf: Process management configuration

### Scripts
- waitforit.sh: Service readiness check script
- entrypoint.sh: Container entrypoint script

### Custom
- version.php: Custom module version information
- log_handler.php: Custom logging handler
- observer.php: Event observer implementation
- events.php: Event definitions

## Version
Current Version: 1.0.0
