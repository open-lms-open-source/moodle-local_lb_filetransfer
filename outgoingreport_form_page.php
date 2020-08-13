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
require($CFG->dirroot.'/local/lb_filetransfer/classes/output/forms/outgoingreports_form.php');

use \core\output\notification;

require_login();
if (!is_siteadmin()) {
    print_error('nopermissions', 'error');
}

$context = context_system::instance();

$id = optional_param('id', null, PARAM_INT);

$data =  new outgoingreports_page($id);
$editoroptions = array(
    'subdirs' => 0,
    'noclean' => true,
    'context' => $context,
    'removeorphaneddrafts' => true
);
$data = file_prepare_standard_editor($data, 'html', $editoroptions, $context, 'local_lb_filetransfer', null, $id);

$title = get_string("config_outgoingreports", 'local_lb_filetransfer');
$PAGE->set_url('/local/lb_filetransfer/outgoingreport_form_page.php');
$PAGE->set_pagelayout('admin');
$PAGE->set_context($context);
$PAGE->navbar->add($title);
$PAGE->set_title($title);
$PAGE->set_heading(get_string("pluginname", 'local_lb_filetransfer'));

$returnurl = new moodle_url($CFG->wwwroot . '/local/lb_filetransfer/outgoingreports.php');
$args = array(
    'editoroptions' => $editoroptions,
    'data' => $data
);

$outgoingreports_page_form = new outgoingreports_form(null, $args);

if ($outgoingreports_page_form->is_cancelled()) {
    redirect(new moodle_url($CFG->wwwroot . '/local/lb_filetransfer/outgoingreports.php'));
} else if ($savedata = $outgoingreports_page_form->get_data()) {
    $outgointreports =  new outgoingreports_page();
    if (empty($savedata->id)) {
        $savedata->active = 1;
    }
    if (empty($savedata->connectionid)) {
        $savedata->connectionid = 0;
    }
    if ((int)$savedata->archivefile == 0) {
        $savedata->archiveperiod = 0;
    } else {
        $savedata->archiveperiod = (int)$savedata->archiveperiod;
    }
    $outgointreports->construct_useruploads_page($savedata);
    if ($outgointreports->save()) {
        $message = get_string('userupload_saved', 'local_lb_filetransfer');
        $messagestyle = notification::NOTIFY_SUCCESS;
        redirect($returnurl, $message, null, $messagestyle);
    } else {
        $message = get_string('userupload_save_error', 'local_lb_filetransfer');
        $messagestyle = notification::NOTIFY_ERROR;
        redirect($returnurl, $message, null, $messagestyle);
    }
    redirect($returnurl, $message, null, $messagestyle);
}

$params = [
    'title' => $title,
    'formhtml' => $outgoingreports_page_form->export_for_template()
];

echo $OUTPUT->header();

echo $OUTPUT->render_from_template('local_lb_filetransfer/add_update_form', $params);

echo $OUTPUT->footer();
