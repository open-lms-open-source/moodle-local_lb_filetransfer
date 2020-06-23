<?php

/**
 * Plugin administration pages are defined here.
 *
 * @package     local_lb_filetransfer
 * @copyright   2020 A K M Safat Shahin <safat@ecreators.com.au>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

if ($hassiteconfig) {
    $ADMIN->add("localplugins", new admin_externalpage('local_lb_filetransfer',
        get_string('pluginname', 'local_lb_filetransfer'), $CFG->wwwroot . "/local/lb_filetransfer/index.php"));
}
