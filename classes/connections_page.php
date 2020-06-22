<?php

/**
 * Plugin administration pages are defined here.
 *
 * @package     local_lb_filetransfer
 * @copyright   2020 A K M Safat Shahin <safat@ecreators.com.au>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;
require($CFG->dirroot.'/local/lb_filetransfer/classes/lb_filetransfer_constants.php');

/**
 * Class connections_page represents a connections object.
 */

class connections_page {

    public $name = null;
    public $connectiontype = lb_filetransfer_constants::CONNECTION_SFTP;
    public $hostname = null;
    public $portnumber = 22;
    public $username = null;
    public $password = null;
    public $usepublickey = 0;
    public $privatekey = null;
    public $active = 0;
    public $usermodified = 0;

    /**
     * connections_page constructor.
     * Builds object if $id provided.
     * @param null $id
     * @throws dml_exception
     */
    public function __construct($id = null) {
        if (!empty($id)) {
            $this->load_connections_page($id);
        }
    }

    /**
     * Constructs the actual Connections object given either a $DB object or Moodle form data.
     * @param $connections
     */
    public function construct_connections_page($connections) {
        if (!empty($connections)) {
            global $USER;
            $this->id = $connections->id;
            $this->name = $connections->name;
            $this->connectiontype = $connections->connectiontype;
            $this->hostname = $connections->hostname;
            $this->portnumber = $connections->portnumber;
            $this->username = $connections->username;
            $this->password = $connections->password;
            $this->usepublickey = $connections->usepublickey;
            $this->privatekey = $connections->privatekey;
            $this->active = $connections->active;
            $this->usermodified = (int)$USER->id;
        }
    }

    /**
     * Delete the connection.
     * @return bool
     * @throws dml_exception
     */
    public function delete() {
        global $DB;
        if (!empty($this->id)) {
            if (!connections_page::data_dependency_check($this->id)) {
                return false;
            }
            return $DB->delete_records('local_lb_filetr_connections', array('id' => $this->id));
        }
        return false;
    }

    /**
     * Deactivate/activate the connection.
     * @return bool
     * @throws dml_exception
     */
    public function activate_deactivate() {
        global $DB;
        if (!empty($this->id)) {
            if (!connections_page::data_dependency_check($this->id)) {
                return false;
            }
            $this->timemodified = time();
            $savesuccess = $DB->update_record('local_lb_filetr_connections', $this);
            if ($savesuccess) {
                return true;
            }
        }
        return false;
    }

    /**
     * Gets the active connections and loads it into the object.
     * @throws dml_exception
     */
    public function load_active_connections() {
        global $DB;
        $connections = $DB->get_record('local_lb_filetr_connections', array('active' => 1));
        $this->construct_connections_page($connections);
    }

    /**
     * Gets the specified connection and loads it into the object.
     * @param $id
     * @throws dml_exception
     */
    private function load_connections_page($id) {
        global $DB;
        $connections_page = $DB->get_record('local_lb_filetr_connections', array('id' => $id));
        $this->construct_connections_page($connections_page);
    }

    public function data_dependency_check($id) {
        global $DB;
        $datadependency = $DB->get_record_sql('SELECT count(id) as datacount
                                                    FROM {local_lb_filetr_uploads} 
                                                    WHERE connectionid = :connectionid',
                                                    array('connectionid' => $id));
        if ($datadependency->datacount > 0) {
            return false;
        }
        return true;
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
            $savesuccess = $DB->update_record('local_lb_filetr_connections', $this);
        } else {
            $this->timecreated = time();
            $this->timemodified = time();
            $this->id = $DB->insert_record('local_lb_filetr_connections', $this);
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
