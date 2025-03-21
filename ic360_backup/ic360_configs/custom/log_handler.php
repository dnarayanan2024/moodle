<?php
namespace local_customlog;

defined('MOODLE_INTERNAL') || die();

class log_handler {
    private $log_path;
    private $max_file_size = 10 * 1024 * 1024; // 10MB
    private $max_files = 5; // Number of backup files to keep

    public function __construct() {
        global $CFG;
        
        // Use the correct Moodle data directory
        $this->log_path = '/var/www/moodledata/custom_logs/';
        
        // Create directory if it doesn't exist
        if (!file_exists($this->log_path)) {
            mkdir($this->log_path, 0777, true);
        }

        // Add debugging
        error_log("Log path: " . $this->log_path);
    }

    public function write_log($log_entry) {
        $current_file = $this->log_path . 'moodle_' . date('Y-m-d') . '.log';
        
        // Add debugging
        error_log("Writing to file: " . $current_file);
        
        // Check if rotation is needed
        $this->rotate_logs($current_file);

        // Format the log entry
        $formatted_log = $this->format_log_entry($log_entry);

        try {
            // Write to file
            $result = file_put_contents(
                $current_file,
                $formatted_log,
                FILE_APPEND | LOCK_EX
            );

            if ($result === false) {
                error_log("Failed to write to log file: " . $current_file);
                error_log("Error: " . error_get_last()['message']);
            } else {
                error_log("Successfully wrote to log file");
            }
        } catch (\Exception $e) {
            error_log("Exception writing to log: " . $e->getMessage());
        }
    }

    private function format_log_entry($log_entry) {
        // Add timestamp and format as JSON
        $log_entry['timestamp'] = date('Y-m-d H:i:s');
        
        return json_encode($log_entry) . "\n";
    }

    private function rotate_logs($current_file) {
        if (file_exists($current_file) && filesize($current_file) > $this->max_file_size) {
            for ($i = $this->max_files - 1; $i > 0; $i--) {
                $old_file = $current_file . '.' . $i;
                $new_file = $current_file . '.' . ($i + 1);
                
                if (file_exists($old_file)) {
                    rename($old_file, $new_file);
                }
            }
            
            rename($current_file, $current_file . '.1');
        }
    }
}


