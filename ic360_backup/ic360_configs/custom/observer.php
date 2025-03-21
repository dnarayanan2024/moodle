<?php
namespace local_customlog;

defined('MOODLE_INTERNAL') || die();

class observer {
    public static function log_entry_created(\core\event\base $event) {
        global $USER, $DB;

        try {
            // Add debugging
            error_log("Observer triggered for event: " . $event->eventname);

            // Get user details
            $username = isset($USER->username) ? $USER->username : 'system';
            $fullname = isset($USER->firstname) && isset($USER->lastname) 
                ? $USER->firstname . ' ' . $USER->lastname 
                : 'System User';

            // Get course details if available
            $coursename = '';
            if (!empty($event->courseid)) {
                try {
                    $course = $DB->get_record('course', array('id' => $event->courseid));
                    if ($course) {
                        $coursename = $course->fullname;
                    }
                } catch (\Exception $e) {
                    $coursename = 'Unknown Course';
                }
            }

            $log_entry = [
                'eventname' => $event->eventname,
                'component' => $event->component,
                'action' => $event->action,
                'target' => $event->target,
                'objecttable' => $event->objecttable,
                'objectid' => $event->objectid,
                'crud' => $event->crud,
                'edulevel' => $event->edulevel,
                'contextid' => $event->contextid,
                'contextlevel' => $event->contextlevel,
                'userid' => $event->userid,
                'courseid' => $event->courseid,
                'coursename' => $coursename,
                'username' => $username,
                'user_fullname' => $fullname,
                'relateduserid' => $event->relateduserid,
                'anonymous' => $event->anonymous,
                'other' => $event->other,
                'timecreated' => $event->timecreated,
                'ip_address' => $_SERVER['REMOTE_ADDR'] ?? 'unknown',
                'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'unknown'
            ];

            // Initialize log handler and write log
            $log_handler = new log_handler();
            $log_handler->write_log($log_entry);

        } catch (\Exception $e) {
            error_log("Error in observer: " . $e->getMessage());
        }
    }
}
