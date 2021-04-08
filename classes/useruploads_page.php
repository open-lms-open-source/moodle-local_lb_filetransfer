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

namespace local_lb_filetransfer;
defined('MOODLE_INTERNAL') || die;

use dml_exception;

/**
 * Class usedruploads_page represents a usedruploads object.
 */
class useruploads_page {

    public $name = null;
    public $connectionid = 0;
    public $pathtofile = null;
    public $filename = null;
    public $getlatestfile = 0;
    public $deleteprocessed = 0;
    public $moveremotefile = 0;
    public $moveremotefiledirectory = null;
    public $movefailedfiles = 0;
    public $movefailedfilesdirectory = null;
    public $archivefile = 0;
    public $archiveperiod = 0;
    public $active = 0;
    public $usermodified = 0;
    public $uutype = 0;
    public $uupasswordnew = 0;
    public $uuupdatetype = 0;
    public $uupasswordold = 0;
    public $allowrename = 0;
    public $allowdeletes = 0;
    public $allowsuspend = 0;
    public $noemailduplicate = 0;
    public $standardusername = 0;
    public $decryptfile = 0;
    public $decryptiontype = 0;
    public $decryptionkey = null;
    public $emaillog = 0;
    public $email = null;

    /**
     * useruploads_page constructor.
     * Builds object if $id provided.
     * @param null $id
     * @throws dml_exception
     */
    public function __construct($id = null) {
        if (!empty($id)) {
            $this->load_useruploads_page($id);
        }
    }

    /**
     * Constructs the actual useruploads object given either a $DB object or Moodle form data.
     * @param $useruploads
     */
    public function construct_useruploads_page($useruploads) {
        if (!empty($useruploads)) {
            global $USER;
            $this->id = $useruploads->id;
            $this->name = $useruploads->name;
            $this->connectionid = $useruploads->connectionid;
            $this->pathtofile = $useruploads->pathtofile;
            $this->filename = $useruploads->filename;
            $this->getlatestfile = $useruploads->getlatestfile;
            $this->deleteprocessed = $useruploads->deleteprocessed;
            $this->moveremotefile = $useruploads->moveremotefile;
            $this->moveremotefiledirectory = $useruploads->moveremotefiledirectory;
            $this->movefailedfiles = $useruploads->movefailedfiles;
            $this->movefailedfilesdirectory = $useruploads->movefailedfilesdirectory;
            $this->archivefile = $useruploads->archivefile;
            $this->archiveperiod = $useruploads->archiveperiod;
            $this->uutype = $useruploads->uutype;
            $this->uupasswordnew = $useruploads->uupasswordnew;
            $this->uuupdatetype = $useruploads->uuupdatetype;
            $this->uupasswordold = $useruploads->uupasswordold;
            $this->allowrename = $useruploads->allowrename;
            $this->allowdeletes = $useruploads->allowdeletes;
            $this->allowsuspend = $useruploads->allowsuspend;
            $this->noemailduplicate = $useruploads->noemailduplicate;
            $this->standardusername = $useruploads->standardusername;
            $this->emaillog = $useruploads->emaillog;
            $this->email = $useruploads->email;
            $this->decryptfile = $useruploads->decryptfile;
            $this->decryptiontype = $useruploads->decryptiontype;
            $this->decryptionkey = $useruploads->decryptionkey;
            $this->active = $useruploads->active;
            $this->usermodified = (int)$USER->id;
        }
    }

    /**
     * Delete the useruploads.
     * @return bool
     * @throws dml_exception
     */
    public function delete() {
        global $DB;
        if (!empty($this->id)) {
            return $DB->delete_records('local_lb_filetr_uploads', array('id' => $this->id));
        }
        return false;
    }

    /**
     * Gets the active useruploads and loads it into the object.
     * @throws dml_exception
     */
    public function load_active_connections() {
        global $DB;
        $useruploads = $DB->get_record('local_lb_filetr_uploads', array('active' => 1));
        $this->construct_useruploads_page($useruploads);
    }

    /**
     * Gets the specified useruploads and loads it into the object.
     * @param $id
     * @throws dml_exception
     */
    private function load_useruploads_page($id) {
        global $DB;
        $useruploads_page = $DB->get_record('local_lb_filetr_uploads', array('id' => $id));
        $this->construct_useruploads_page($useruploads_page);
    }

    /**
     * Gets all the active connections.
     * @return array
     * @throws dml_exception
     */
    public function get_connections () {
        global $DB;
        $connectiontype = array();
        $connections = $DB->get_records_sql('SELECT lfc.id, lfc.name
                                                  FROM {local_lb_filetr_connections} lfc
                                                  WHERE lfc.active = :active',
                                                  array('active' => 1));
        foreach ($connections as $connection) {
            $connectiontype[$connection->id] = $connection->name;
        }
        return $connectiontype;
    }

    /**
     * Upsert function.
     * @return bool
     * @throws dml_exception
     */
    public function save() {
        global $DB;

        $savesuccess = false;
        if (!empty($this->id)) {
            $this->timemodified = time();
            $savesuccess = $DB->update_record('local_lb_filetr_uploads', $this);
        } else {
            $this->timecreated = time();
            $this->timemodified = time();
            $this->id = $DB->insert_record('local_lb_filetr_uploads', $this);
            if (!empty($this->id)) {
                $savesuccess = true;
            }
        }
        if ($savesuccess) {
            return true;
        }
        return false;
    }
}
