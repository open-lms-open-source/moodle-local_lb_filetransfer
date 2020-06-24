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
 * Class lb_filetransfer_report_helper represents the helper functions available outgoing report.
 */
class lb_filetransfer_report_helper {

    public $connectiontype = 0;
    public $hostname = null;
    public $portnumber = 0;
    public $username = null;
    public $password = null;
    public $usepublickey = 0;
    public $privatekey = null;
    public $configurablereportid = 0;
    public $pathtofile = null;
    public $filename = null;
    public $archivefile = 0;
    public $archiveperiod = 0;
    public $email = null;


    /**
     * lb_filetransfer_helper constructor.
     * Builds object if $id provided.
     * @param null $object
     * @param null $objectid
     * @throws dml_exception
     */
    public function __construct($objectid = null) {
        if (!empty($objectid)) {
            $this->load_directory( $objectid);
        }
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
            $this->configurablereportid = $directory->configurablereportid;
            $this->pathtofile = $directory->pathtofile;
            $this->filename = $directory->filename;
            $this->archivefile = $directory->archivefile;
            $this->archiveperiod = $directory->archiveperiod;
            $this->email = $directory->email;
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
     * @param $objectid
     * @throws dml_exception
     */
    private function load_directory($objectid) {
        global $DB;
        $directory = $DB->get_record('local_lb_filetr_reports', array('id' => $objectid));
        $this->load_connections((int)$directory->connectionid);
        $this->construct_directory($directory);
    }

    /**
     * validates email.
     * @param $email
     * @return bool
     */
    public function validate_emails($email)
    {
        if (!empty($email)) {
            $domain = ltrim(stristr($email, '@'), '@') . '.';
            $user = stristr($email, '@', TRUE);
            if (!empty($user) && !empty($domain) && checkdnsrr($domain)) {
                return true;
            }
        }
        return false;
    }

    /**
     * constructs emails.
     * @return array
     */
    public function construct_email() {
        $emails = explode (",", $this->email);
        foreach ($emails as $key => $email) {
            $emails[$key] = trim($email);
            //email validation
            if (!self::validate_emails($email)) {
                unset($emails[$key]);
            }
        }
        return $emails;
    }

    /**
     * Tests the connection and directory.
     * @return bool
     */
    public function test_connection () {
        $host = $this->hostname;
        if (empty($host)) {
//            eventDescription (get_string('connectionerrornohost', 'local_lb_filetransfer'));
            return false;
        }
        $port = $this->portnumber;
        $username = $this->username;
        $sftp = new Net_SFTP($host, $port);
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
        if (empty($filename)) {
            return false;
        }
        return true;
    }

}
