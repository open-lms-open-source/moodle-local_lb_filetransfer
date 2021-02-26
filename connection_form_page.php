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
require($CFG->dirroot.'/local/lb_filetransfer/classes/connections_page.php');
require($CFG->dirroot.'/local/lb_filetransfer/classes/output/forms/connection_form.php');

use \core\output\notification;

require_login();
if (!is_siteadmin()) {
    print_error('nopermissions', 'error');
}

$context = context_system::instance();

$id = optional_param('id', null, PARAM_INT);

$data =  new connections_page($id);
$editoroptions = array(
    'subdirs' => 0,
    'noclean' => true,
    'context' => $context,
    'removeorphaneddrafts' => true
);
$data = file_prepare_standard_editor($data, 'html', $editoroptions, $context, 'local_lb_filetransfer', null, $id);

$title = get_string("config_connections", 'local_lb_filetransfer');
$PAGE->set_url('/local/lb_filetransfer/connections_form_page.php');
$PAGE->set_pagelayout('admin');
$PAGE->set_context($context);
$PAGE->navbar->add($title);
$PAGE->set_title($title);
$PAGE->set_heading(get_string("pluginname", 'local_lb_filetransfer'));

$returnurl = new moodle_url($CFG->wwwroot . '/local/lb_filetransfer/connections.php');
$args = array(
    'editoroptions' => $editoroptions,
    'data' => $data
);

$connection_page_form = new connection_form(null, $args);

if ($connection_page_form->is_cancelled()) {
    redirect(new moodle_url($CFG->wwwroot . '/local/lb_filetransfer/connections.php'));
} else if ($savedata = $connection_page_form->get_data()) {
    $connections =  new connections_page();
    if (empty($savedata->id)) {
        $savedata->active = 1;
    }
    $connections->construct_connections_page($savedata);
    if ($connections->save()) {
        $message = get_string('connection_saved', 'local_lb_filetransfer');
        $messagestyle = notification::NOTIFY_SUCCESS;
        redirect($returnurl, $message, null, $messagestyle);
    } else {
        $message = get_string('connection_save_error', 'local_lb_filetransfer');
        $messagestyle = notification::NOTIFY_ERROR;
        redirect($returnurl, $message, null, $messagestyle);
    }
    redirect($returnurl, $message, null, $messagestyle);
}

$params = [
    'title' => $title,
    'formhtml' => $connection_page_form->export_for_template()
];

echo $OUTPUT->header();

echo $OUTPUT->render_from_template('local_lb_filetransfer/add_update_form', $params);

echo $OUTPUT->footer();












