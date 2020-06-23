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
 * Class connection_form.
 * An extension of your usual Moodle form.
 */

class connection_form extends moodleform {

    /**
     * Defines the custom connection_form.
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

        $connectiontype = array();
        $connectiontype[] = $mform->createElement('radio', 'connectiontype', '', get_string('connection_sftp','local_lb_filetransfer'), 1);
        //for future addition of different connection types
        //$connectiontype[] = $mform->createElement('radio', 'connectiontype', '', get_string('connection_ftp', 'local_lb_filetransfer'), 2);
        $mform->addGroup($connectiontype, 'connections', get_string('connectiontype', 'local_lb_filetransfer'), array(' '), false);
        $mform->setDefault('connectiontype', 1);

        $mform->addElement('text', 'hostname', get_string('hostname','local_lb_filetransfer'));
        $mform->addRule('hostname', get_string('required'), 'required', null, 'client');
        $mform->addRule('hostname',get_string('maximum_character_255', 'local_lb_filetransfer'), 'maxlength', 255, 'client');
        $mform->setType('hostname', PARAM_TEXT);

        $mform->addElement('text', 'portnumber', get_string('portnumber','local_lb_filetransfer'));
        $mform->addRule('portnumber', get_string('required'), 'required', null, 'client');
        $mform->addRule('portnumber',get_string('number_only', 'local_lb_filetransfer'), 'numeric', null, 'client');
        $mform->setType('portnumber', PARAM_TEXT);

        $mform->addElement('text', 'username', get_string('username','local_lb_filetransfer'));
        $mform->addRule('username',get_string('maximum_character_255', 'local_lb_filetransfer'), 'maxlength', 255, 'client');
        $mform->setType('username', PARAM_TEXT);

        $mform->addElement('password', 'password', get_string('password','local_lb_filetransfer'));
        $mform->addRule('password',get_string('maximum_character_255', 'local_lb_filetransfer'), 'maxlength', 255, 'client');
        $mform->setType('password', PARAM_TEXT);

        $usepublickey = array();
        $usepublickey[] = $mform->createElement('radio', 'usepublickey', '', get_string('no', 'local_lb_filetransfer'), 0);
        $usepublickey[] = $mform->createElement('radio', 'usepublickey', '', get_string('yes','local_lb_filetransfer'), 1);
        $mform->addGroup($usepublickey, 'usepublickeygr', get_string('usepublickey', 'local_lb_filetransfer'), array(' '), false);
        $mform->setDefault('usepublickey', 0);

        $mform->addElement('textarea', 'privatekey', get_string('privatekey','local_lb_filetransfer'));
        $mform->addRule('privatekey',get_string('maximum_character_1024', 'local_lb_filetransfer'), 'maxlength', 1024, 'client');
        $mform->setType('privatekey', PARAM_TEXT);

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
