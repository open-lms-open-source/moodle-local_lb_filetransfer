<?php

/**
 * Plugin administration pages are defined here.
 *
 * @package     local_lb_filetransfer
 * @copyright   2020 A K M Safat Shahin <safat@ecreators.com.au>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require('../../config.php');
require_once($CFG->libdir.'/adminlib.php');
require($CFG->dirroot.'/local/lb_filetransfer/classes/connections_page.php');
require($CFG->dirroot.'/local/lb_filetransfer/classes/output/tables/connections_table.php');

use \core\output\notification;

require_login();
admin_externalpage_setup('local_lb_filetransfer');

$id = optional_param('id', null, PARAM_INT);
$action = optional_param('action', '', PARAM_TEXT);
$confirm = optional_param('confirm', 0, PARAM_BOOL);

$context = context_system::instance();

$title = get_string("config_connections", 'local_lb_filetransfer');
$PAGE->set_url('/local/lb_filetransfer/connections.php');
$PAGE->set_pagelayout('admin');
$PAGE->set_context($context);

$returnurl = new moodle_url($CFG->wwwroot . '/local/lb_filetransfer/connections.php');

$connections =  new connections_page($id);
//table actions
if (!empty($connections->id)) {
    if ($action == 'delete') {
        $PAGE->url->param('action', 'delete');
        $a = new stdClass();
        $a->name = $connections->name;
        if ($confirm and confirm_sesskey()) {
            $active_connection = $connections->active;
            if ($connections->delete()) {
                $message = get_string('connection_deleted', 'local_lb_filetransfer', $a);
                $messagestyle = notification::NOTIFY_SUCCESS;
            } else {
                $message = get_string('connection_delete_failed', 'local_lb_filetransfer', $a);
                $messagestyle = notification::NOTIFY_ERROR;
            }
            redirect($returnurl, $message, null, $messagestyle);
        }
        $strheading = get_string('delete_conneciton', 'local_lb_filetransfer');
        $PAGE->navbar->add($strheading);
        $PAGE->set_title($strheading);
        $PAGE->set_heading($strheading);

        echo $OUTPUT->header();

        $yesurl = new moodle_url($CFG->wwwroot . '/local/lb_filetransfer/connections.php', array(
            'id' => $connections->id, 'action' => 'delete', 'confirm' => 1, 'sesskey' => sesskey()
        ));
        $message = get_string('delete_connection_confirmation', 'local_lb_filetransfer', $a);
        echo $OUTPUT->confirm($message, $yesurl, $returnurl);
        echo $OUTPUT->footer();
        die;
    }
    if (confirm_sesskey()) {
        if ($action == 'show') {
            $a = new stdClass();
            $a->name = $connections->name;
            $message = get_string('connection_active', 'local_lb_filetransfer', $a);
            $messagestyle = notification::NOTIFY_SUCCESS;
            if (!$connections->active) {
                $connections->active = 1;
                if (!$connections->save()) {
                    $message = get_string('connection_active_error', 'local_lb_filetransfer', $a);
                    $messagestyle = notification::NOTIFY_ERROR;
                }
            }
            redirect($returnurl, $message, null, $messagestyle);
        } else if ($action == 'hide') {
            $a = new stdClass();
            $a->name = $connections->name;
            $message = get_string('connection_deactive', 'local_lb_filetransfer', $a);
            $messagestyle = notification::NOTIFY_SUCCESS;
            // Don't bother doing anything if it's already inactive.
            if ($connections->active) {
                $connections->active = 0;
                if (!$connections->save()) {
                    $message = get_string('connection_deactive_error', 'local_lb_filetransfer', $a);
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

$connection_table = new connections_table('connections_table');

$params = [
    'title' => $title,
    'previousurl' =>  new moodle_url($CFG->wwwroot . '/local/lb_filetransfer/index.php'),
    'editurl' => new moodle_url($CFG->wwwroot . '/local/lb_filetransfer/connection_form_page.php'),
    'tablehtml' => $connection_table->export_for_template()
];

echo $OUTPUT->header();

echo $OUTPUT->render_from_template('local_lb_filetransfer/general_table', $params);

echo $OUTPUT->footer();

