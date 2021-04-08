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
use dml_exception;
use moodleform;
use local_lb_filetransfer\useruploads_page;

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

        $getlatestfile = array();
        $getlatestfile[] = $mform->createElement('radio', 'getlatestfile', '', get_string('no', 'local_lb_filetransfer'), 0);
        $getlatestfile[] = $mform->createElement('radio', 'getlatestfile', '', get_string('yes','local_lb_filetransfer'), 1);
        $mform->addGroup($getlatestfile, 'getlatestfile', get_string('getlatestfile', 'local_lb_filetransfer'), array(' '), false);
        $mform->setDefault('getlatestfile', 0);

        $deleteprocessed = array();
        $deleteprocessed[] = $mform->createElement('radio', 'deleteprocessed', '', get_string('no', 'local_lb_filetransfer'), 0);
        $deleteprocessed[] = $mform->createElement('radio', 'deleteprocessed', '', get_string('yes','local_lb_filetransfer'), 1);
        $mform->addGroup($deleteprocessed, 'deleteprocessed', get_string('delete_processed', 'local_lb_filetransfer'), array(' '), false);
        $mform->setDefault('deleteprocessed', 0);

        $moveremotefile = array();
        $moveremotefile[] = $mform->createElement('radio', 'moveremotefile', '', get_string('no', 'local_lb_filetransfer'), 0);
        $moveremotefile[] = $mform->createElement('radio', 'moveremotefile', '', get_string('yes','local_lb_filetransfer'), 1);
        $mform->addGroup($moveremotefile, 'moveremotefile', get_string('move_remotefile', 'local_lb_filetransfer'), array(' '), false);
        $mform->setDefault('moveremotefile', 0);

        $mform->addElement('text', 'moveremotefiledirectory', get_string('move_remotefile_directory','local_lb_filetransfer'));
        $mform->addRule('moveremotefiledirectory',get_string('maximum_character_255', 'local_lb_filetransfer'), 'maxlength', 255, 'client');

        $movefailedfiles = array();
        $movefailedfiles[] = $mform->createElement('radio', 'movefailedfiles', '', get_string('no', 'local_lb_filetransfer'), 0);
        $movefailedfiles[] = $mform->createElement('radio', 'movefailedfiles', '', get_string('yes','local_lb_filetransfer'), 1);
        $mform->addGroup($movefailedfiles, 'movefailedfiles', get_string('move_failed_files', 'local_lb_filetransfer'), array(' '), false);
        $mform->setDefault('movefailedfiles', 0);

        $mform->addElement('text', 'movefailedfilesdirectory', get_string('move_failed_files_directory','local_lb_filetransfer'));
        $mform->addRule('movefailedfilesdirectory',get_string('maximum_character_255', 'local_lb_filetransfer'), 'maxlength', 255, 'client');

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

        $uutype = array();
        $uutype[0] = get_string('uuoptype_addnew', 'local_lb_filetransfer');
        $uutype[1] = get_string('uuoptype_addinc', 'local_lb_filetransfer');
        $uutype[2] = get_string('uuoptype_addupdate', 'local_lb_filetransfer');
        $uutype[3] = get_string('uuoptype_update', 'local_lb_filetransfer');
        $mform->addElement('select', 'uutype', get_string('uutype', 'local_lb_filetransfer'), $uutype);
        $mform->setDefault('uutype', 2);

        $uupasswordnew = array();
        $uupasswordnew[0] = get_string('infilefield', 'local_lb_filetransfer');
        $uupasswordnew[1] = get_string('createpasswordifneeded', 'local_lb_filetransfer');
        $mform->addElement('select', 'uupasswordnew', get_string('uupasswordnew', 'local_lb_filetransfer'), $uupasswordnew);
        $mform->setDefault('uupasswordnew', 1);

        $uuupdatetype = array();
        $uuupdatetype[0] = get_string('nochanges', 'local_lb_filetransfer');
        $uuupdatetype[1] = get_string('uuupdatefromfile', 'local_lb_filetransfer');
        $uuupdatetype[2] = get_string('uuupdateall', 'local_lb_filetransfer');
        $uuupdatetype[3] = get_string('uuupdatemissing', 'local_lb_filetransfer');
        $mform->addElement('select', 'uuupdatetype', get_string('uuupdatetype', 'local_lb_filetransfer'), $uuupdatetype);
        $mform->setDefault('uuupdatetype', 2);

        $uupasswordold = array();
        $uupasswordold[0]  = get_string('nochanges', 'local_lb_filetransfer');
        $uupasswordold[1] = get_string('update', 'local_lb_filetransfer');
        $mform->addElement('select', 'uupasswordold', get_string('uupasswordold', 'local_lb_filetransfer'), $uupasswordold);
        $mform->setDefault('uupasswordold', 0);

        $allowrename = array();
        $allowrename[1]  = get_string('yes', 'local_lb_filetransfer');
        $allowrename[0] = get_string('no', 'local_lb_filetransfer');
        $mform->addElement('select', 'allowrename', get_string('allowrename', 'local_lb_filetransfer'), $allowrename);
        $mform->setDefault('allowrename', 1);

        $allowdeletes = array();
        $allowdeletes[1]  = get_string('yes', 'local_lb_filetransfer');
        $allowdeletes[0] = get_string('no', 'local_lb_filetransfer');
        $mform->addElement('select', 'allowdeletes', get_string('allowdeletes', 'local_lb_filetransfer'), $allowdeletes);
        $mform->setDefault('allowdeletes', 1);

        $allowsuspend = array();
        $allowsuspend[1]  = get_string('yes', 'local_lb_filetransfer');
        $allowsuspend[0] = get_string('no', 'local_lb_filetransfer');
        $mform->addElement('select', 'allowsuspend', get_string('allowsuspend', 'local_lb_filetransfer'), $allowsuspend);
        $mform->setDefault('allowsuspend', 1);

        $noemailduplicate = array();
        $noemailduplicate[1]  = get_string('yes', 'local_lb_filetransfer');
        $noemailduplicate[0] = get_string('no', 'local_lb_filetransfer');
        $mform->addElement('select', 'noemailduplicate', get_string('noemailduplicate', 'local_lb_filetransfer'), $noemailduplicate);
        $mform->setDefault('noemailduplicate', 0);

        $standardusername = array();
        $standardusername[1]  = get_string('yes', 'local_lb_filetransfer');
        $standardusername[0] = get_string('no', 'local_lb_filetransfer');
        $mform->addElement('select', 'standardusername', get_string('standardusername', 'local_lb_filetransfer'), $standardusername);
        $mform->setDefault('standardusername', 0);

        $decryptfile = array();
        $decryptfile[] = $mform->createElement('radio', 'decryptfile', '', get_string('no', 'local_lb_filetransfer'), 0);
        $decryptfile[] = $mform->createElement('radio', 'decryptfile', '', get_string('yes','local_lb_filetransfer'), 1);
        $mform->addGroup($decryptfile, 'decryptfile', get_string('decryptfile', 'local_lb_filetransfer'), array(' '), false);
        $mform->setDefault('decryptfile', 0);

        $decryptiontype = array();
        $decryptiontype[] = $mform->createElement('radio', 'decryptiontype', '', get_string('decryptiontype_aes','local_lb_filetransfer'), 1);
        $mform->addGroup($decryptiontype, 'decryptiontypegrp', get_string('decryptiontype', 'local_lb_filetransfer'), array(' '), false);
        $mform->setDefault('decryptiontype', 1);

        $mform->addElement('textarea', 'decryptionkey', get_string('decryptionkey','local_lb_filetransfer'));
        $mform->addRule('decryptionkey',get_string('maximum_character_1024', 'local_lb_filetransfer'), 'maxlength', 1024, 'client');
        $mform->setType('decryptionkey', PARAM_TEXT);

        $emaillog = array();
        $emaillog[] = $mform->createElement('radio', 'emaillog', '', get_string('no', 'local_lb_filetransfer'), 0);
        $emaillog[] = $mform->createElement('radio', 'emaillog', '', get_string('yes','local_lb_filetransfer'), 1);
        $mform->addGroup($emaillog, 'emaillog', get_string('emaillog', 'local_lb_filetransfer'), array(' '), false);
        $mform->setDefault('emaillog', 0);

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
