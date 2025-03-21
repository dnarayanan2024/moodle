<?php
defined('MOODLE_INTERNAL') || die();

$observers = [
    [
        'eventname' => '*',  // Listen to all events
        'callback' => '\local_customlog\observer::log_entry_created',
        'includefile' => null,
        'internal' => false,
        'priority' => 0
    ]
];
