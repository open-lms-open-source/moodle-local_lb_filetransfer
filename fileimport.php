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
 * @copyright   2021 eCreators PTY LTD
 * @author      2021 A K M Safat Shahin <safat@ecreators.com.au>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require(__DIR__.'/../../config.php');
global $CFG, $PAGE, $OUTPUT;
require_once($CFG->libdir.'/adminlib.php');

use \core\output\notification;
use local_lb_filetransfer\fileimport_page;
use local_lb_filetransfer\output\tables\fileimport_table;

require_login();
admin_externalpage_setup('local_lb_filetransfer');

$id = optional_param('id', null, PARAM_INT);
$action = optional_param('action', '', PARAM_TEXT);
$confirm = optional_param('confirm', 0, PARAM_BOOL);

$context = context_system::instance();

$title = get_string("config_fileimport", 'local_lb_filetransfer');
$PAGE->set_url('/local/lb_filetransfer/fileimport.php');
$PAGE->set_pagelayout('admin');
$PAGE->set_context($context);

$returnurl = new moodle_url($CFG->wwwroot . '/local/lb_filetransfer/fileimport.php');

$fileimport =  new fileimport_page($id);

//table actions
if (!empty($fileimport->id)) {
    if ($action == 'delete') {
        $PAGE->url->param('action', 'delete');
        $a = new stdClass();
        $a->name = $fileimport->name;
        if ($confirm and confirm_sesskey()) {
            if ($fileimport->delete()) {
                $message = get_string('fileimport_deleted', 'local_lb_filetransfer', $a);
                $messagestyle = notification::NOTIFY_SUCCESS;
            } else {
                $message = get_string('fileimport_delete_failed', 'local_lb_filetransfer', $a);
                $messagestyle = notification::NOTIFY_ERROR;
            }
            redirect($returnurl, $message, null, $messagestyle);
        }
        $strheading = get_string('delete_fileimport', 'local_lb_filetransfer');
        $PAGE->navbar->add($strheading);
        $PAGE->set_title($strheading);
        $PAGE->set_heading($strheading);

        echo $OUTPUT->header();

        $yesurl = new moodle_url($CFG->wwwroot . '/local/lb_filetransfer/fileimport.php', array(
            'id' => $fileimport->id, 'action' => 'delete', 'confirm' => 1, 'sesskey' => sesskey()
        ));
        $message = get_string('delete_fileimport_confirmation', 'local_lb_filetransfer', $a);
        echo $OUTPUT->confirm($message, $yesurl, $returnurl);
        echo $OUTPUT->footer();
        die;
    }
    if (confirm_sesskey()) {
        if ($action == 'show') {
            $a = new stdClass();
            $a->name = $fileimport->name;
            $message = get_string('fileimport_active', 'local_lb_filetransfer', $a);
            $messagestyle = notification::NOTIFY_SUCCESS;
            if (!$fileimport->active) {
                $fileimport->active = 1;
                if (!$fileimport->save()) {
                    $message = get_string('fileimport_active_error', 'local_lb_filetransfer', $a);
                    $messagestyle = notification::NOTIFY_ERROR;
                }
            }
            redirect($returnurl, $message, null, $messagestyle);
        } else if ($action == 'hide') {
            $a = new stdClass();
            $a->name = $fileimport->name;
            $message = get_string('fileimport_deactive', 'local_lb_filetransfer', $a);
            $messagestyle = notification::NOTIFY_SUCCESS;
            // Don't bother doing anything if it's already inactive.
            if ($fileimport->active) {
                $fileimport->active = 0;
                if (!$fileimport->save()) {
                    $message = get_string('fileimport_deactive_error', 'local_lb_filetransfer', $a);
                    $messagestyle = notification::NOTIFY_ERROR;
                }
            }
            redirect($returnurl, $message, null, $messagestyle);
        }
    }
}

$PAGE->navbar->add($title);
$PAGE->set_title($title);
$PAGE->set_heading(get_string("pluginname", 'local_lb_filetransfer'));

$fileimport_table = new fileimport_table('fileimport_table');

$params = [
    'title' => $title,
    'previousurl' =>  new moodle_url($CFG->wwwroot . '/local/lb_filetransfer/index.php'),
    'editurl' => new moodle_url($CFG->wwwroot . '/local/lb_filetransfer/fileimport_form_page.php'),
    'tablehtml' => $fileimport_table->export_for_template()
];

echo $OUTPUT->header();

echo $OUTPUT->render_from_template('local_lb_filetransfer/general_table', $params);

echo $OUTPUT->footer();
