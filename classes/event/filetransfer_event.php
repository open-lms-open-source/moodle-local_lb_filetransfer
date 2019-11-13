<?php

/**
 * Plugin version and other meta-data are defined here.
 *
 * @package     local_lb_filetransfer
 * @copyright   2019 A K M Safat Shahin <safat@ecreators.com.au>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_lb_filetransfer\event;

use core\event\base;

defined('MOODLE_INTERNAL') || die();

class filetransfer_event extends base {

    protected function init() {
        $this->data['crud'] = 'u';
        $this->data['edulevel'] = self::LEVEL_OTHER;
        $this->context = \context_system::instance();
    }

    public static function get_name() {
        return get_string('filetransferevent', 'local_lb_filetransfer');
    }

    public function get_description() {
        $desc = $this->data['other'];
        return $desc;
    }
}
