<?php

/**
 * Plugin administration pages are defined here.
 *
 * @package     local_learnbookfiletransfer
 * @category    admin
 * @copyright   2019 A K M Safat Shahin <safat@ecreators.com.au>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_learnbookfiletransfer\task;

defined('MOODLE_INTERNAL') || die();

class filetransfer extends \core\task\scheduled_task {

    public function get_name() {
        return get_string('filetransfertask', 'local_learnbookfiletransfer');
    }

    public function execute() {
        global $CFG;
        require_once($CFG->dirroot . '/local/learnbookfiletransfer/filetransfer.php');
        getFile();
    }
}
