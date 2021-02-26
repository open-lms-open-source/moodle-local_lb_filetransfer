<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.
/**
 * Plugin administration pages are defined here.
 *
 * @package     local_lb_filetransfer
 * @category    admin
 * @copyright   2020 eCreators PTY LTD
 * @author      2020 A K M Safat Shahin <safat@ecreators.com.au>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_lb_filetransfer\task;

defined('MOODLE_INTERNAL') || die();

global $CFG;
require($CFG->dirroot.'/local/lb_filetransfer/classes/lb_filetransfer_outgoingreport.php');

class outgoingreport extends \core\task\scheduled_task {

    public function get_name() {
        return get_string('filetransfertask_report', 'local_lb_filetransfer');
    }

    public function execute() {
        (new \lb_filetransfer_outgoingreport)->send_outgoingreport();
    }
}
