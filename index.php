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
 * @copyright   2020 eCreators PTY LTD
 * @author      2020 A K M Safat Shahin <safat@ecreators.com.au>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require('../../config.php');
require_once($CFG->libdir.'/adminlib.php');

require_login();
admin_externalpage_setup('local_lb_filetransfer');

$context = context_system::instance();
$PAGE->requires->css('/local/lb_filetransfer/assets/css/lb_filetransfer.css');
$title = get_string("pluginname", 'local_lb_filetransfer');
$PAGE->set_url('/local/lb_filetransfer/index.php');
$PAGE->set_pagelayout('admin');
$PAGE->set_context($context);
$PAGE->navbar->add($title);
$PAGE->set_title($title);
$PAGE->set_heading($title);
$params = [];
echo $OUTPUT->header();
echo $OUTPUT->render_from_template('local_lb_filetransfer/index_page', $params);
echo $OUTPUT->footer();
