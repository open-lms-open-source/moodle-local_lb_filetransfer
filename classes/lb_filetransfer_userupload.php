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
 * Class lb_filetransfer_userupload represents all the available constants.
 */
class lb_filetransfer_userupload {

    public function archiveDir ($localdir,$filename) {
        //fielsystem api to be used in the next phase
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
     * Gets the connections and userupload instance.
     * @return bool
     * @throws dml_exception
     * @throws coding_exception
     */
    public function get_userupload_file() {
        global $CFG, $DB, $USER;
        $get_userupload_instances = $DB->get_records_sql('SELECT *
                                                               FROM {local_lb_filetr_uploads}
                                                               WHERE active = :active',
                                                               array('active' => 1));
        foreach ($get_userupload_instances as $userupload) {
            $connection = new lb_filetransfer_helper(0, (int)$userupload->id);
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
                    $today = time();  // Make the date stamp
                    $today = date("Y-m-d-h-m-s",$today);
                    $newName = $fileToUpload.'_renamed_'.$today;
                    //if existing file found, renamed
                    rename( $fileToUpload, $newName);
                }

                //Transferred the file from remote directory.
                $sftp->get($remotedir.$filename, $fileToUpload);
                //Changed permission of the file to 0777.
                chmod($fileToUpload,0777);
                //Starting user upload.




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
                    mtrace("No file was found at ".$filepath);
                    $status = true;
                }
                $readcount = $cir->load_csv_content($content, $encoding, $delimiter_name);
                $csvloaderror = $cir->get_error();
                mtrace('Posting form data to mform.');
                $formdata = new stdClass();
                $_POST['iid'] = $iid;
                $_POST['previewrows'] = '10';
                //$_POST['uutype'] = UU_USER_ADD_UPDATE;
                $_POST['uutype'] = (int)$connection->uutype;
                //$_POST['uupasswordnew'] = '1';
                $_POST['uupasswordnew'] = (int)$connection->uupasswordnew;
                //$_POST['uuupdatetype'] = UU_UPDATE_ALLOVERRIDE;
                $_POST['uuupdatetype'] = (int)$connection->uuupdatetype;
                //$_POST['uupasswordold'] = '0';
                $_POST['uupasswordold'] = (int)$connection->uupasswordold;
                //$_POST['uuforcepasswordchange'] = UU_PWRESET_NONE;
                $_POST['uuforcepasswordchange'] = (int)$connection->uupasswordold;
                //$_POST['allowrenames'] = '0';
                $_POST['allowrenames'] = (int)$connection->allowrename;
                //$_POST['uuallowdeletes'] = '0';
                $_POST['uuallowdeletes'] = (int)$connection->allowdeletes;
                //$_POST['uuallowsuspends'] = '1';
                $_POST['uuallowsuspends'] = (int)$connection->allowsuspend;
                //$_POST['uunoemailduplicates'] = '1';
                $_POST['uunoemailduplicates'] = (int)$connection->noemailduplicate;
                //$_POST['uustandardusernames'] = '1';
                $_POST['uustandardusernames'] = (int)$connection->standardusername;
                $_POST['uubulk'] = UU_BULK_ALL;
                //$_POST['uuallowrenames'] = '0';
                $_POST['uuallowrenames'] = (int)$connection->allowrename;

                /* //cohort select plugin doesn't accept csv upload. When that plugin is changed, this feature can be used.
                $cohortSelect = cohortSelect();
                if (!empty($cohortSelect)) {
                    $_POST[$cohortSelect] = '_qf__force_multiselect_submission';
                }*/

                $_POST['_qf__admin_uploaduser_form2'] = '1';
                $_POST['submitbutton'] = 'Upload users';
                $_POST['sesskey'] = sesskey();
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
                $_POST['uutype'] = (int)$connection->uutype;
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
                    mtrace('Code executed.');
                }
                catch (Exception $e) {
                    $output = ob_get_clean();
                    mtrace('Code execution error.');
                    $status = false;
                }

                mtrace('Codes executed, preventing any output from echo statements.');
                $output = ob_get_clean();

                mtrace('Users upload successfull.');
                $status = true;
                if ($status) {
                    //add event
                    mtrace("File transfer process completed.");
                }
                else {
                    //add event
                    mtrace("File transfer process not completed.");
                }

                if ((int)$connection->archivefile) {
                    mtrace("Archiving uploaded file.");
                    $path = self::archiveDir($localdir,$filename);
                    $days = (int)$connection->archiveperiod;
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
                //add event
            } else {
                //add event
                mtrace("Connection error");
            }
        }
    }
}
