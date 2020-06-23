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
require($CFG->dirroot.'/local/lb_filetransfer/classes/outgoingreports_page.php');
require($CFG->dirroot.'/local/lb_filetransfer/classes/output/tables/outgoingreports_table.php');

use \core\output\notification;

require_login();
admin_externalpage_setup('local_lb_filetransfer');

$id = optional_param('id', null, PARAM_INT);
$action = optional_param('action', '', PARAM_TEXT);
$confirm = optional_param('confirm', 0, PARAM_BOOL);

$context = context_system::instance();

$title = get_string("config_outgoingreports", 'local_lb_filetransfer');
$PAGE->set_url('/local/lb_filetransfer/outgoingreports.php');
$PAGE->set_pagelayout('admin');
$PAGE->set_context($context);

$returnurl = new moodle_url($CFG->wwwroot . '/local/lb_filetransfer/outgoingreports.php');

$outgoingreports =  new outgoingreports_page($id);

//table actions
if (!empty($outgoingreports->id)) {
    if ($action == 'delete') {
        $PAGE->url->param('action', 'delete');
        $a = new stdClass();
        $a->name = $outgoingreports->name;
        if ($confirm and confirm_sesskey()) {
            if ($outgoingreports->delete()) {
                $message = get_string('outgoingreport_deleted', 'local_lb_filetransfer', $a);
                $messagestyle = notification::NOTIFY_SUCCESS;
            } else {
                $message = get_string('outgoingreport_delete_failed', 'local_lb_filetransfer', $a);
                $messagestyle = notification::NOTIFY_ERROR;
            }
            redirect($returnurl, $message, null, $messagestyle);
        }
        $strheading = get_string('delete_outgoingreport', 'local_lb_filetransfer');
        $PAGE->navbar->add($strheading);
        $PAGE->set_title($strheading);
        $PAGE->set_heading($strheading);

        echo $OUTPUT->header();

        $yesurl = new moodle_url($CFG->wwwroot . '/local/lb_filetransfer/outgoingreports.php', array(
            'id' => $outgoingreports->id, 'action' => 'delete', 'confirm' => 1, 'sesskey' => sesskey()
        ));
        $message = get_string('delete_outgoingreport_confirmation', 'local_lb_filetransfer', $a);
        echo $OUTPUT->confirm($message, $yesurl, $returnurl);
        echo $OUTPUT->footer();
        die;
    }
    if (confirm_sesskey()) {
        if ($action == 'show') {
            $a = new stdClass();
            $a->name = $outgoingreports->name;
            $message = get_string('outgoingreport_active', 'local_lb_filetransfer', $a);
            $messagestyle = notification::NOTIFY_SUCCESS;
            if (!$outgoingreports->active) {
                $outgoingreports->active = 1;
                if (!$outgoingreports->save()) {
                    $message = get_string('outgoingreport_active_error', 'local_lb_filetransfer', $a);
                    $messagestyle = notification::NOTIFY_ERROR;
                }
            }
            redirect($returnurl, $message, null, $messagestyle);
        } else if ($action == 'hide') {
            $a = new stdClass();
            $a->name = $outgoingreports->name;
            $message = get_string('outgoingreport_deactive', 'local_lb_filetransfer', $a);
            $messagestyle = notification::NOTIFY_SUCCESS;
            // Don't bother doing anything if it's already inactive.
            if ($outgoingreports->active) {
                $outgoingreports->active = 0;
                if (!$outgoingreports->save()) {
                    $message = get_string('outgoingreport_deactive_error', 'local_lb_filetransfer', $a);
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

$outgointreports_table = new outgoingreports_table('useruploads_table');

$params = [
    'title' => $title,
    'previousurl' =>  new moodle_url($CFG->wwwroot . '/local/lb_filetransfer/index.php'),
    'editurl' => new moodle_url($CFG->wwwroot . '/local/lb_filetransfer/outgoingreport_form_page.php'),
    'tablehtml' => $outgointreports_table->export_for_template()
];

echo $OUTPUT->header();

echo $OUTPUT->render_from_template('local_lb_filetransfer/general_table', $params);

echo $OUTPUT->footer();