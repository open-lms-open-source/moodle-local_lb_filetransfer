<?php

/**
 * Plugin administration pages are defined here.
 *
 * @package     local_lb_filetransfer
 * @category    admin
 * @copyright   2020 A K M Safat Shahin <safat@ecreators.com.au>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_lb_filetransfer\task;

defined('MOODLE_INTERNAL') || die();
global $CFG;
require($CFG->dirroot.'/local/lb_filetransfer/classes/lb_filetransfer_userupload.php');
class filetransfer extends \core\task\scheduled_task {

    public function get_name() {
        return get_string('filetransfertask', 'local_lb_filetransfer');
    }

    public function execute() {

        (new \lb_filetransfer_userupload)->get_userupload_file();
    }
}
