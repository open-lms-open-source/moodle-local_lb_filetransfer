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
require_once($CFG->libdir.'/adminlib.php');
require_once($CFG->libdir.'/csvlib.class.php');
require_once($CFG->dirroot.'/user/profile/lib.php');
require_once($CFG->dirroot.'/user/lib.php');
require_once($CFG->dirroot.'/group/lib.php');
require_once($CFG->dirroot.'/cohort/lib.php');
require_once($CFG->dirroot.'/admin/tool/uploaduser/locallib.php');
require_once($CFG->dirroot.'/admin/tool/uploaduser/user_form.php');
require($CFG->dirroot.'/local/lb_filetransfer/classes/lb_filetransfer_helper.php');

/**
 * Class lb_filetransfer_userupload represents all the available userupload object.
 */
class lb_filetransfer_userupload {

    /**
     * Triggers event.
     * @param $description
     * @return string
     * @throws coding_exception
     */
    public function eventTrigger($description) {
        $event = \local_lb_filetransfer\event\filetransfer_event::create(array(
            'other' => $description
        ));
        $event->trigger();
    }

    /**
     * Archives directory.
     * @param $localdir
     * @param $filename
     * @return string
     */
    public function archive_directory($localdir, $filename) {
        global $CFG;
        if (file_exists ($CFG->dataroot . '/temp/lb_filetransfer/lb_filetransfer_backup')) {
            mtrace("Backup folder already exists, using existing one.");
            $backupdir = 'lb_filetransfer_backup/';
        }
        else {
            make_temp_directory('lb_filetransfer/lb_filetransfer_backup/');
            $backupdir = 'lb_filetransfer_backup/';
            mtrace("Created backup folder for archiving.");
        }
        $today = time();
        $today = date("Y-m-d-h-m-s",$today);
        $newExtension = '_processed_'.$today;
        $newName = $localdir.$filename.$newExtension;
        rename( $localdir.$filename, $newName);
        mtrace("Renamed the file to archive.");
        copy($newName, $localdir.$backupdir.$filename.$newExtension);
        mtrace("Copied the file to backup directory.");
        unlink($newName);
        mtrace("Removed the file from the temp directory");
        $archiveDir = $localdir.$backupdir;
        mtrace("Archiving completed");
        return $archiveDir;
    }

    /**
     * Creates local directory.
     * @return string
     */
    public function create_local_dir() {
        global $CFG;
        if (file_exists ($CFG->dataroot . '/temp/lb_filetransfer')) {
            //Temporary folder already exists, using existing one.
            $tempdir = 'lb_filetransfer/';
            return $tempdir;
        }
        make_temp_directory('lb_filetransfer/');
        //Created temp folder for the file.
        $tempdir = 'lb_filetransfer/';
        return $tempdir;
    }

    /**
     * Cohort select future update changes.
     * @return string
     * @throws dml_exception
     */
    public function cohort_select() {
        global $DB;
        $sql = "SELECT shortname
            FROM {user_info_field}
            WHERE datatype = 'cohortselect'";
        $dbOutput = $DB->get_record_sql($sql)->shortname;
        //var_dump($dbOutput);die;
        if (!empty($dbOutput)) {
            $dbOutput = 'profile_field_'.$dbOutput;
        }
        return $dbOutput;
    }

    /**
     * Gets the file and uploads users.
     * @param $fileToUpload
     * @param $connection
     * @return bool
     */
    public function user_upload ($fileToUpload, $connection) {
        global $CFG, $USER, $DB;
        $admin = get_admin();
        $USER = $admin;
        mtrace('Setting encoding and delimiter.');
        $encoding = 'UTF-8';
        $delimiter_name = 'comma';
        mtrace('Processing the file.');
        $filepath = $fileToUpload;
        $iid = csv_import_reader::get_new_iid('uploaduser');
        $cir = new csv_import_reader($iid, 'uploaduser');
        $content = file_get_contents($filepath);
        if(!$content) {
            mtrace("No content was found at ".$filepath);
            return true;
        }
        $readcount = $cir->load_csv_content($content, $encoding, $delimiter_name);
        $csvloaderror = $cir->get_error();
        mtrace('Posting form data to mform.');
        $formdata = new stdClass();
        $_POST['iid'] = $iid;
        $_POST['previewrows'] = '10';
        $_POST['uutype'] = (int)$connection->uutype;
        $_POST['uupasswordnew'] = $connection->uupasswordnew;
        $_POST['uuupdatetype'] = (int)$connection->uuupdatetype;
        $_POST['uupasswordold'] = $connection->uupasswordold;
        $_POST['uuforcepasswordchange'] = (int)$connection->uupasswordold;
        $_POST['allowrenames'] = $connection->allowrename;
        $_POST['uuallowdeletes'] = $connection->allowdeletes;
        $_POST['uuallowsuspends'] = $connection->allowsuspend;
        $_POST['uunoemailduplicates'] = $connection->noemailduplicate;
        $_POST['uustandardusernames'] = $connection->standardusername;
        $_POST['uubulk'] = UU_BULK_ALL;
        $_POST['uuallowrenames'] = '0';
        $_POST['_qf__admin_uploaduser_form2'] = '1';
        $_POST['submitbutton'] = 'Upload users';
        $_POST['sesskey'] = sesskey();

        //default values from moodle which will always work
        /*
        $_POST['iid'] = $iid;
        $_POST['previewrows'] = '10';
        $_POST['uutype'] = UU_USER_ADD_UPDATE;
        $_POST['uupasswordnew'] = '1';
        $_POST['uuupdatetype'] = UU_UPDATE_ALLOVERRIDE;
        $_POST['uupasswordold'] = '0';
        $_POST['uuforcepasswordchange'] = UU_PWRESET_NONE;
        $_POST['allowrenames'] = '0';
        $_POST['uuallowdeletes'] = '0';
        $_POST['uuallowsuspends'] = '1';
        $_POST['uunoemailduplicates'] = '1';
        $_POST['uustandardusernames'] = '1';
        $_POST['uubulk'] = UU_BULK_ALL;
        $_POST['uuallowrenames'] = '0';
        $_POST['_qf__admin_uploaduser_form2'] = '1';
        $_POST['submitbutton'] = 'Upload users';
        $_POST['sesskey'] = sesskey();
        */


        /* //cohort select plugin doesn't accept csv upload. When that plugin is changed, this feature can be used.
        $cohortSelect = cohort_select();
        if (!empty($cohortSelect)) {
            $_POST[$cohortSelect] = '_qf__force_multiselect_submission';
        }*/

        /*
        $choices = uu_allowed_roles(true);
        if ($studentroles = get_archetype_roles('student')) {
            foreach ($studentroles as $role) {
                if (isset($choices[$role->id])) {
                    $_POST['uulegacy1'] = $role->id;
                    break;
                }
            }
            unset($studentroles);
        }
        if ($editteacherroles = get_archetype_roles('editingteacher')) {
            foreach ($editteacherroles as $role) {
                if (isset($choices[$role->id])) {
                    $_POST['uulegacy2'] = $role->id;
                    break;
                }
            }
            unset($editteacherroles);
        }
        if ($teacherroles = get_archetype_roles('teacher')) {
            foreach ($teacherroles as $role) {
                if (isset($choices[$role->id])) {
                    $_POST['uulegacy3'] = $role->id;
                    break;
                }
            }
            unset($teacherroles);
        }
        */

        $templateuser = $USER;

        mtrace('Setting up the default profile fields.');
        $_POST['auth'] = 'manual';
        $_POST['maildisplay'] = 2;
        $_POST['mailformat'] = 1;
        $_POST['maildigest'] = 0;
        $_POST['autosubscribe'] = 1;
        $_POST['city'] = '';
        $_POST['country'] = $templateuser->country;
        $_POST['timezone'] = $templateuser->timezone;
        $_POST['lang'] = $templateuser->lang;
        $_POST['description'] = '';
        $_POST['url'] = '';
        $_POST['idnumber'] = '';
        $_POST['institution'] = '';
        $_POST['department'] = '';
        $_POST['phone1'] = '';
        $_POST['phone2'] = '';
        $_POST['address'] = '';
        $_POST['uutype'] = $connection->uutype;
        $_POST['submitbutton'] = 'submit';

        mtrace('Getting the moodle upload user codes.');
        ob_start();
        chdir($CFG->dirroot . '/admin/tool/uploaduser');
        $indexfile = file_get_contents($CFG->dirroot . '/admin/tool/uploaduser/index.php');
        $indexfile = str_replace("<?php","",$indexfile);
        $indexfile = str_replace("require('../../../config.php');",'', $indexfile);
        $indexfile = str_replace("echo $OUTPUT", "// echo $OUTPUT", $indexfile);
        $indexfile = str_replace("die;", "return;", $indexfile);

        mtrace('Executing the codes.');
        try {
            eval($indexfile);
        }
        catch (Exception $e) {
            $output = ob_get_clean();
            return false;
        }

        mtrace('Codes executed, preventing any output from echo statements.');
        $output = ob_get_clean();

        mtrace('Users upload successfull.');
        return true;
    }

    /**
     * Gets the files and archives them.
     * @param $archive_enabled
     * @param $archive_period
     * @param $localdir
     * @param $filename
     * @param $tempdir
     * @return void
     */
    public function archive_file ($archive_enabled, $archive_period, $localdir, $filename, $tempdir) {
        if ($archive_enabled) {
            mtrace("Archiving uploaded file.");
            $path = self::archive_directory($localdir,$filename);
            $days = $archive_period;
            // Open the directory
            if ($handle = opendir($path))
            {
                // Loop through the directory
                while (false !== ($file = readdir($handle)))
                {
                    // Check the file we're doing is actually a file
                    if (is_file($path.$file))
                    {
                        // Check if the file is older than X days old
                        if (filemtime($path.$file) < ( time() - ( $days * 24 * 60 * 60 ) ) )
                        {
                            // Do the deletion
                            unlink($path.$file);
                        }
                    }
                }
            }
            mtrace("Deleted old files.");
        } else {
            fulldelete($tempdir.$filename);
            mtrace("Deleted temporary file.");
        }
    }

    /**
     * Gets the connections and userupload instance.
     * @return void
     * @throws dml_exception|coding_exception
     */
    public function get_userupload_file() {
        global $CFG, $DB, $USER;
        $get_userupload_instances = $DB->get_records_sql('SELECT *
                                                               FROM {local_lb_filetr_uploads}
                                                               WHERE active = :active',
                                                               array('active' => 1));
        foreach ($get_userupload_instances as $userupload) {
            $connection = new lb_filetransfer_helper((int)$userupload->id);
            if($connection->test_connection()) {
                //add event
                $host = $connection->hostname;
                $port = (int)$connection->portnumber;
                $username = $connection->username;
                $remotedir = $connection->pathtofile;
                $filename = $connection->filename;
                $tempdir = self::create_local_dir();
                $localdir = $CFG->dataroot . '/temp/'.$tempdir;
                $sftp = new Net_SFTP($host, $port);
                $fileToUpload = $localdir.$filename;

                if ((int)$connection->usepublickey == 0) {
                    $password = $connection->password;
                    $sftp->login($username, $password);
                    //Password login successful.
                } else {
                    $key = new Crypt_RSA();
                    $key->loadKey($connection->privatekey);
                    $sftp->login($username, $key);
                    //RSA login successful.
                }

                //Going to remote directory.
                $sftp->chdir($remotedir);
                //Checking if the file exist or not.
                if (file_exists($fileToUpload)) {
                    fulldelete($fileToUpload);
                }

                //Transfer the file from remote directory.
                if ((int)$connection->getlatestfile == 0) {
                    $remotefile = $remotedir.$filename;
                } else {
                    $remote_files = $sftp->rawlist();
                    $compare_size = sizeof($remote_files);
                    foreach ($remote_files as $key => $remote_file) {
                        $time = $remote_file["mtime"];
                        $counter = 0;
                        foreach ($remote_files as $keys => $file) {
                            if ($time >= $file["mtime"]) {
                                $counter++;
                            }
                        }
                        if ($counter == $compare_size) {
                            $selected_file = $remote_file;
                        }
                    }
                    $remotefile = $remotedir.$selected_file["filename"];
                }
                $sftp->get($remotefile, $fileToUpload);


                //Changed permission of the file to 0777.
                chmod($fileToUpload,0777);

                //starting upload
                $userupload_status = self::user_upload($fileToUpload, $connection);

                //file archive
                self::archive_file((int)$connection->archivefile, (int)$connection->archiveperiod, $localdir, $filename, $tempdir);
                $a = new stdClass();
                $a->id = $userupload->id;
                $log_data[] = get_string('filetransfertask_userfilearchive', 'local_lb_filetransfer', $a);
                self::eventTrigger(get_string('filetransfertask_userfilearchive', 'local_lb_filetransfer', $a));

                if ($userupload_status) {
                    $today = time();  // Make the date stamp
                    $today = date("Y-m-d-h-m-s",$today);
                    $processed_filename = 'Processed_'.$today.'_'.$connection->filename;
                    $sftp->put($connection->moveremotefiledirectory.$processed_filename, $fileToUpload);
                    $sftp->delete($remotefile);
                    $a = new stdClass();
                    $a->id = $userupload->id;
                    self::eventTrigger(get_string('filetransfertask_userupload', 'local_lb_filetransfer', $a));
                    mtrace("File transfer process completed.");
                }
                else {
                    $today = time();  // Make the date stamp
                    $today = date("Y-m-d-h-m-s",$today);
                    $failed_filename = 'Failed_'.$today.'_'.$connection->filename;
                    $sftp->put($connection->movefailedfilesdirectory.$failed_filename, $fileToUpload);
                    $sftp->delete($remotefile);
                    $a = new stdClass();
                    $a->id = $userupload->id;
                    self::eventTrigger(get_string('filetransfertask_userupload_error', 'local_lb_filetransfer', $a));
                    mtrace("File transfer process not completed.");
                }
                $sftp->disconnect();
            } else {
                $a = new stdClass();
                $a->id = $userupload->id;
                self::eventTrigger(get_string('filetransfertask_connection_error', 'local_lb_filetransfer', $a));
                mtrace("Connection error");
            }
        }
    }

}
