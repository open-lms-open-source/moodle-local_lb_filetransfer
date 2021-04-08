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

namespace local_lb_filetransfer\output\forms;
defined('MOODLE_INTERNAL') || die();
global $CFG;
require_once($CFG->dirroot . '/lib/formslib.php');

use coding_exception;
use local_lb_filetransfer\outgoingreports_page;
use moodleform;

/**
 * Class outgoingreports_form.
 * An extension of your usual Moodle form.
 */
class outgoingreports_form extends moodleform {

    /**
     * Defines the custom outgoingreports_form.
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
        $mform->setDefault('connectionid', 0);

        $configurablereportid = (new outgoingreports_page)->get_configurable_reports();
        $mform->addElement('select', 'configurablereportid', get_string('configurablereportid', 'local_lb_filetransfer'), $configurablereportid);
        $mform->addRule('configurablereportid', get_string('required'), 'required', null, 'client');
        $mform->setDefault('configurablereportid', 0);

        $mform->addElement('text', 'pathtofile', get_string('pathtofile','local_lb_filetransfer'));
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

        $encryptfile = array();
        $encryptfile[] = $mform->createElement('radio', 'encryptfile', '', get_string('no', 'local_lb_filetransfer'), 0);
        $encryptfile[] = $mform->createElement('radio', 'encryptfile', '', get_string('yes','local_lb_filetransfer'), 1);
        $mform->addGroup($encryptfile, 'encryptfilegrp', get_string('encryptfile', 'local_lb_filetransfer'), array(' '), false);
        $mform->setDefault('encryptfile', 0);

        $encryptiontype = array();
        $encryptiontype[] = $mform->createElement('radio', 'encryptiontype', '', get_string('decryptiontype_aes','local_lb_filetransfer'), 1);
        $mform->addGroup($encryptiontype, 'encryptiontypegrp', get_string('encryptiontype', 'local_lb_filetransfer'), array(' '), false);
        $mform->setDefault('encryptiontype', 1);

        $mform->addElement('textarea', 'encryptionkey', get_string('encryptionkey','local_lb_filetransfer'));
        $mform->addRule('encryptionkey',get_string('maximum_character_1024', 'local_lb_filetransfer'), 'maxlength', 1024, 'client');
        $mform->setType('encryptionkey', PARAM_TEXT);

        $mform->addElement('textarea', 'privatekey', get_string('privatekey','local_lb_filetransfer'));
        $mform->addRule('privatekey',get_string('maximum_character_1024', 'local_lb_filetransfer'), 'maxlength', 1024, 'client');
        $mform->setType('privatekey', PARAM_TEXT);

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
