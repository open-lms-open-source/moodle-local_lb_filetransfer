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
