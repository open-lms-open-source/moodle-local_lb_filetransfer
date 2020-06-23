<?php
/**
 * Plugin administration pages are defined here.
 *
 * @package     local_lb_filetransfer
 * @copyright   2020 A K M Safat Shahin <safat@ecreators.com.au>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

set_include_path(get_include_path(). PATH_SEPARATOR . $CFG->dirroot.'/local/lb_filetransfer/lib/phpseclib');
require_once($CFG->dirroot .'/local/lb_filetransfer/lib/phpseclib/Net/SFTP.php');
require_once($CFG->dirroot .'/local/lb_filetransfer/lib/phpseclib/Crypt/RSA.php');

/**
 * Class lb_filetransfer_helper represents a lb_filetransfer_helper object.
 */
class lb_filetransfer_helper {

    public $connectiontype = 0;
    public $hostname = null;
    public $portnumber = 0;
    public $username = null;
    public $password = null;
    public $usepublickey = 0;
    public $privatekey = null;
    public $pathtofile = null;
    public $filename = null;
    public $archivefile = 0;
    public $archiveperiod = 0;
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

    /**
     * lb_filetransfer_helper constructor.
     * Builds object if $id provided.
     * @param null $object
     * @param null $objectid
     * @throws dml_exception
     */
    public function __construct($object = null, $objectid = null) {
        $this->load_directory($object, $objectid);
    }

    /**
     * Constructs the actual connection object given either a $DB object or Moodle form data.
     * @param $connections
     */
    public function construct_connection($connections) {
        if (!empty($connections)) {
            $this->connectiontype = $connections->connectiontype;
            $this->hostname = $connections->hostname;
            $this->portnumber = $connections->portnumber;
            $this->username = $connections->username;
            $this->password = $connections->password;
            $this->usepublickey = $connections->usepublickey;
            $this->privatekey = $connections->privatekey;
        }
    }

    /**
     * Constructs the actual directory object given either a $DB object or Moodle form data.
     * @param $directory
     */
    public function construct_directory($directory) {
        if (!empty($directory)) {
            $this->pathtofile = $directory->pathtofile;
            $this->filename = $directory->filename;
            $this->archivefile = $directory->archivefile;
            $this->archiveperiod = $directory->archiveperiod;
            $this->uutype = $directory->uutype;
            $this->uupasswordnew = $directory->uupasswordnew;
            $this->uuupdatetype = $directory->uuupdatetype;
            $this->uupasswordold = $directory->uupasswordold;
            $this->allowrename = $directory->allowrename;
            $this->allowdeletes = $directory->allowdeletes;
            $this->allowsuspend = $directory->allowsuspend;
            $this->noemailduplicate = $directory->noemailduplicate;
            $this->standardusername = $directory->standardusername;
        }
    }

    /**
     * Gets the specified connection and loads it into the object.
     * @param $id
     * @throws dml_exception
     */
    private function load_connections($id) {
        global $DB;
        $connections = $DB->get_record('local_lb_filetr_connections', array('id' => $id));
        $this->construct_connection($connections);
    }

    /**
     * Gets the specified directory and loads it into the object.
     * @param $object
     * @param $objectid
     * @throws dml_exception
     */
    private function load_directory($object, $objectid) {
        global $DB;
        if ($object == 0) {
            $directory = $DB->get_record('local_lb_filetr_uploads',
                                                array('id' => $objectid));
            $this->load_connections((int)$directory->connectionid);
            $this->construct_directory($directory);
        }
    }

    public function test_connection () {
        $host = $this->hostname;
        if (empty($host)) {
//            eventDescription (get_string('connectionerrornohost', 'local_lb_filetransfer'));
            return false;
        }

        $port = $this->portnumber;
        $username = $this->username;
        $sftp = new Net_SFTP($host, $port);

        if (empty($this->pathtofile)) {
            $remotedir = '/';
        }
        else {
            $remotedir = $this->pathtofile;
        }

        $filename = $this->filename;

        if ($this->usepublickey == 0) {
            $password = $this->password;
            if (!$sftp->login($username, $password)) {

//                eventDescription (get_string('connectionerrorpassword', 'local_lb_filetransfer'));
                return false;
            }
        } else {
            $key = new Crypt_RSA();
            $key->loadKey($this->privatekey);
            if (!$sftp->login($username, $key)) {

//                eventDescription (get_string('connectionerrorrsa', 'local_lb_filetransfer'));
                return false;
            }
        }
        if (!empty($filename)) {
            if ($sftp->file_exists($remotedir . $filename)) {
                if (!$sftp->is_readable($remotedir . $filename)) {

//                    eventDescription (get_string('filedirectoryerror', 'local_lb_filetransfer'));
                    return false;
                }
            } else {

//                eventDescription (get_string('filedirectoryerrornomatch', 'local_lb_filetransfer'));
                return false;
            }
        } else {

//            eventDescription (get_string('filedirectoryerrornofile', 'local_lb_filetransfer'));
            return false;
        }
        return true;
    }

}
