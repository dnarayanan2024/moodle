<?php

$envFile = __DIR__ . '/.env';

if (file_exists($envFile)) {
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos($line, '=') !== false) {
            putenv($line);
        }
    }
}

unset($CFG);
global $CFG;
$CFG = new stdClass();

// Database configuration
$CFG->dbtype    = 'pgsql';
$CFG->dblibrary = 'native';
$CFG->dbhost    = getenv("MOODLE_DB_HOST");
$CFG->dbname    = getenv("MOODLE_DB_NAME");
$CFG->dbuser    = getenv("MOODLE_DB_USER");
$CFG->dbpass    = getenv("MOODLE_DB_PASSWORD");
$CFG->prefix    = 'mdl_';
$CFG->dboptions = array('dbpersist' => false, 'dbsocket' => false);

#$CFG->wwwroot   = 'http://54.241.162.243:82';  // Update to HTTPS when available
$CFG->wwwroot   = getenv("MOODLE_WWWROOT");
$CFG->dataroot  = '/var/www/moodledata';
$CFG->admin     = getenv("MOODLE_ADMINPATH");
$CFG->directorypermissions = 0755;

// Production settings
$CFG->debug = 0;
$CFG->debugdisplay = 0;
$CFG->passwordpolicy = true;
$CFG->cronclionly = true;
$CFG->tool_securityquestions_enabled = true;
$CFG->cookiesecure = true;
$CFG->allowthemechangeonurl = false;
$CFG->disableupdateautodeploy = true;
$CFG->disableupdatenotifications = true;

// Performance settings
$CFG->slasharguments = true;
$CFG->theme = 'boost';
$CFG->themerev = 1;
$CFG->cssoptimise = true;
$CFG->cachejs = true;
$CFG->enablecssoptimiser = true;
$CFG->enablecompression = true;

// Redis configuration
$CFG->redis = array(
    'host' => getenv("REDIS_SERVER"),
    'port' => getenv("REDIS_PORT"),
    'database' => 0,
    'password' => getenv('REDIS_PASSWORD'),
);

// Caching configuration
$CFG->cachestore = 'redis';
$CFG->cache_store_backend_redis = $CFG->redis;

// Session handling
$CFG->session_handler_class = '\core\session\redis';
$CFG->session_redis_host = $CFG->redis['host'];
$CFG->session_redis_port = $CFG->redis['port'];
$CFG->session_redis_database = $CFG->redis['database'];
$CFG->session_redis_auth = $CFG->redis['password'];

// Cache Configuration
//$CFG->cachestore = 'redis';
//$CFG->cache_store_backend_redis = [
//    'host'     => getenv('REDIS_HOST'),
//    'port'     => getenv('REDIS_PORT'),
 //   'database' => getenv('REDIS_DATABASE'),
 //   'password' => getenv('REDIS_PASSWORD'),
//];

// Session Handling
//$CFG->session_handler_class = '\core\session\redis';
//$CFG->session_redis_host     = getenv('REDIS_HOST');
//$CFG->session_redis_port     = getenv('REDIS_PORT');
//$CFG->session_redis_database = getenv('REDIS_DATABASE');
//$CFG->session_redis_auth     = getenv('REDIS_PASSWORD');

// Security settings
$CFG->passwordpolicy = true;
$CFG->minpasswordlength = 10;
$CFG->minpassworddigits = 1;
$CFG->minpasswordlower = 1;
$CFG->minpasswordupper = 1;
$CFG->minpasswordnonalphanum = 1;
$CFG->maxloginattempts = 5;
$CFG->lockoutthreshold = 10;
$CFG->lockoutwindow = 60 * 15;
$CFG->lockoutduration = 60 * 30;

// Backup settings
$CFG->backup_auto_active = true;
$CFG->backup_auto_storage = true;
$CFG->backup_auto_destination = $CFG->dataroot . '/repository/backups';

// Email settings (configure these for your SMTP server)
// $CFG->smtphosts = 'smtp.example.com:587';
// $CFG->smtpsecure = 'tls';
// $CFG->smtpauthtype = 'LOGIN';
// $CFG->smtpuser = 'your_username';
// $CFG->smtppass = 'your_password';

// Timezone
$CFG->timezone = 'UTC';

// Language settings
$CFG->lang = 'en';

// Mobile app
$CFG->enablemobilewebservice = true;

// Logging
$CFG->logguests = false;
$CFG->loglifetime = 0; // Never delete logs

// Prevent direct access to certain directories
$CFG->preventexecpath = true;

// Maintenance mode
// $CFG->maintenance_enabled = false;

// Force HTTPS for logins and cookies
$CFG->cookiesecure = true;
$CFG->loginhttps = true;

// Require password change on first login
$CFG->passwordchangelogout = true;

// Enable scheduled tasks
$CFG->cron_enabled = true;

// File permissions for new folders
$CFG->directorypermissions = 02777;

// Prevent session cookies from being accessed by JavaScript
$CFG->cookiehttponly = true;

// Set the session timeout (in seconds)
$CFG->sessiontimeout = 7200;

// Disable guest auto-login
$CFG->guestloginbutton = false;

// Reverse proxy settings
#$CFG->reverseproxy = true;
$CFG->sslproxy = true;

require_once(__DIR__ . '/lib/setup.php');
