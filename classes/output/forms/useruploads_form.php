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
 * Class useruploads_form.
 * An extension of your usual Moodle form.
 */

class useruploads_form extends moodleform {

    /**
     * Defines the custom useruploads_form.
     * @throws dml_exception
     * @throws coding_exception
     */
    public function definition() {
        global $DB;
        $mform = $this->_form;
        $data = $this->_customdata['data'];

        $mform->addElement('hidden', 'id');
        $mform->setType('id', PARAM_INT);

        $mform->addElement('text', 'name', get_string('name','local_lb_filetransfer'));
        $mform->addRule('name', get_string('required'), 'required', null, 'client');
        $mform->addRule('name',get_string('maximum_character_255', 'local_lb_filetransfer'), 'maxlength', 255, 'client');
        $mform->setType('name', PARAM_TEXT);

        $connectiontype = (new useruploads_page)->get_connections();
        $mform->addElement('select', 'connectionid', get_string('connectionid', 'local_lb_filetransfer'), $connectiontype);
        $mform->addRule('connectionid', get_string('required'), 'required', null, 'client');
        $mform->setDefault('connectionid', 0);


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
