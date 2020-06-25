<?php

/**
 * Plugin administration pages are defined here.
 *
 * @package     local_lb_filetransfer
 * @copyright   2020 A K M Safat Shahin <safat@ecreators.com.au>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/lib/formslib.php');

/**
 * Class outgoingreports_form.
 * An extension of your usual Moodle form.
 */
class outgoingreports_form extends moodleform {

    /**
     * Defines the custom outgoingreports_form.
     * @throws dml_exception
     * @throws coding_exception
     */
    public function definition() {
        $mform = $this->_form;
        $data = $this->_customdata['data'];

        $mform->addElement('hidden', 'id');
        $mform->setType('id', PARAM_INT);

        $mform->addElement('text', 'name', get_string('name','local_lb_filetransfer'));
        $mform->addRule('name', get_string('required'), 'required', null, 'client');
        $mform->addRule('name',get_string('maximum_character_255', 'local_lb_filetransfer'), 'maxlength', 255, 'client');
        $mform->setType('name', PARAM_TEXT);

        $outgoingreportpreference = array();
        $outgoingreportpreference[] = $mform->createElement('radio', 'outgoingreportpreference', '', get_string('outgoingreportpreference_remote', 'local_lb_filetransfer'), 0);
        $outgoingreportpreference[] = $mform->createElement('radio', 'outgoingreportpreference', '', get_string('outgoingreportpreference_email','local_lb_filetransfer'), 1);
        $outgoingreportpreference[] = $mform->createElement('radio', 'outgoingreportpreference', '', get_string('outgoingreportpreference_both','local_lb_filetransfer'), 2);
        $mform->addGroup($outgoingreportpreference, 'outgoingreportpreference', get_string('outgoingreportpreference', 'local_lb_filetransfer'), array(' '), false);
        $mform->setDefault('outgoingreportpreference', 0);

        $connectiontype = (new outgoingreports_page)->get_connections();
        $mform->addElement('select', 'connectionid', get_string('connectionid', 'local_lb_filetransfer'), $connectiontype);
//        $mform->addRule('connectionid', get_string('required'), 'required', null, 'client');
        $mform->setDefault('connectionid', 0);

        $configurablereportid = (new outgoingreports_page)->get_configurable_reports();
        $mform->addElement('select', 'configurablereportid', get_string('configurablereportid', 'local_lb_filetransfer'), $configurablereportid);
        $mform->addRule('configurablereportid', get_string('required'), 'required', null, 'client');
        $mform->setDefault('configurablereportid', 0);

        $mform->addElement('text', 'pathtofile', get_string('pathtofile','local_lb_filetransfer'));
        $mform->addRule('pathtofile', get_string('required'), 'required', null, 'client');
        $mform->addRule('pathtofile',get_string('maximum_character_255', 'local_lb_filetransfer'), 'maxlength', 255, 'client');
        $mform->setType('pathtofile', PARAM_TEXT);

        $mform->addElement('text', 'filename', get_string('filename','local_lb_filetransfer'));
        $mform->addRule('filename', get_string('required'), 'required', null, 'client');
        $mform->addRule('filename',get_string('maximum_character_255', 'local_lb_filetransfer'), 'maxlength', 255, 'client');

        $archivefile = array();
        $archivefile[] = $mform->createElement('radio', 'archivefile', '', get_string('no', 'local_lb_filetransfer'), 0);
        $archivefile[] = $mform->createElement('radio', 'archivefile', '', get_string('yes','local_lb_filetransfer'), 1);
        $mform->addGroup($archivefile, 'archivefilegr', get_string('archivefile', 'local_lb_filetransfer'), array(' '), false);
        $mform->setDefault('archivefile', 0);

        $archiveperiod = array();
        $archiveperiod[] = $mform->createElement('radio', 'archiveperiod', '', get_string('twoweeks','local_lb_filetransfer'), 14);
        $archiveperiod[] = $mform->createElement('radio', 'archiveperiod', '', get_string('fourweeks', 'local_lb_filetransfer'), 28);
        $mform->addGroup($archiveperiod, 'archiveperiodgr', get_string('archiveperiod', 'local_lb_filetransfer'), array(' '), false);
        $mform->setDefault('archiveperiod', 0);

        $emailpreference = array();
        $emailpreference[] = $mform->createElement('radio', 'emailpreference', '', get_string('emailpreference_report', 'local_lb_filetransfer'), 0);
        $emailpreference[] = $mform->createElement('radio', 'emailpreference', '', get_string('emailpreference_log','local_lb_filetransfer'), 1);
        $emailpreference[] = $mform->createElement('radio', 'emailpreference', '', get_string('emailpreference_both','local_lb_filetransfer'), 2);
        $mform->addGroup($emailpreference, 'emailpreference', get_string('emailpreference', 'local_lb_filetransfer'), array(' '), false);
        $mform->setDefault('emailpreference', 0);

        $mform->addElement('text', 'email', get_string('email','local_lb_filetransfer'));
        $mform->addRule('email',get_string('maximum_character_255', 'local_lb_filetransfer'), 'maxlength', 255, 'client');
        $mform->setType('email', PARAM_TEXT);

        $mform->addElement('hidden', 'active');
        $mform->setType('active', PARAM_INT);

        $this->add_action_buttons();
        $this->set_data($data);
    }

    /**
     * Export this data so it can be used as the context for a mustache template.
     *
     * @return string
     */
    public function export_for_template() {
        ob_start();
        $this->display();
        $formhtml = ob_get_contents();
        ob_end_clean();

        return $formhtml;
    }
}
