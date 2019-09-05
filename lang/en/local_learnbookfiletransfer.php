<?php

/**
 * Plugin strings are defined here.
 *
 * @package     local_learnbookfiletransfer
 * @category    string
 * @copyright   2019 A K M Safat Shahin <safat@ecreators.com.au>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$string['pluginname'] = 'Learnbook File Transfer';
$string['pluginname_description'] = 'Learnbook File Transfer plugin to automate user upload using CSV file from a remote repository.';

$string['host'] = 'Host name';
$string['hostdesc'] = 'URL of the host.';

$string['port'] = 'Port Number';
$string['portdesc'] = 'Port number of the connection.';

$string['username'] = 'Username';
$string['usernamedesc'] = 'Enter the username of the connection.';

$string['password'] = 'Password';
$string['passworddesc'] = 'Enter the password of the connection.';

$string['enableskey'] = 'Use public key authentication';
$string['enableskeydesc'] = 'Enable or disable public key authentication.';

$string['rsatoken'] = 'Private key';
$string['rsatokendesc'] = 'Enter private key to authenticate via SFTP.';

$string['remotedir'] = 'Path to file';
$string['remotedirdesc'] = 'Location of the remote directory, e.g. /directory/';

$string['masterfile'] = 'File name';
$string['masterfiledesc'] = 'Name of the file to upload.';

$string['emailenable'] = 'Email log report';
$string['emailenabledesc'] = 'Enable or disable the email to get the log report.';

$string['emaillog'] = 'Receiver emails';
$string['emaillogdesc'] = 'Enter the emails to send the log report, e.g. example@email.com,example2@email.com.';

$string['connectionerror'] = 'Connection Status: Error connecting to the server, please check your connection information';
$string['connectionerrorpassword'] = 'Connection Status: Error connecting to the server, please check your username and password.';
$string['connectionerrorrsa'] = 'Connection Status: Error connecting to the server, please check your username and RSA key.';
$string['connectionerrornohost'] = 'Connection Status: Hostname is empty, please specify the hostname';
$string['connectionsuccess'] = 'Connection Status: Successfully connected to the server.';

$string['filetransfertask'] = 'Learnbook File trasfer integration.';
$string['filearchiveenable'] = 'Enable processed file archiving.';
$string['filearchiveenabledesc'] = 'Files will be archived in the lb_filetransfer_backup directory upto 4 weeks.';
$string['filearchiveperiod'] = 'Select your file archive period';
$string['filearchiveperioddesc'] = 'Selected period will be used to retain processed CSV files.';

$string['connectionsetting'] = 'Connection Settings';
$string['filesetting'] = 'File and Directory Settings';
$string['uploadsetting'] = 'User Upload Settings';

$string['uutype'] = 'Upload type';
$string['uuoptype_addinc'] = 'Add all, append number to usernames if needed';
$string['uuoptype_addnew'] = 'Add new only, skip existing users';
$string['uuoptype_addupdate'] = 'Add new and update existing users';
$string['uuoptype_update'] = 'Update existing users only';

$string['uupasswordnew'] = 'New user password';
$string['infilefield'] = 'Field required in file';
$string['createpasswordifneeded'] = 'Create password if needed and send via email';

$string['uuupdatetype'] = 'Existing user details';
$string['nochanges'] = 'No changes';
$string['uuupdatefromfile'] = 'Override with file';
$string['uuupdateall'] = 'Override with file and defaults';
$string['uuupdatemissing'] = 'Fill in missing from file and defaults';

$string['uupasswordold'] = 'Existing user password';
$string['nochanges'] = 'No changes';
$string['update'] = 'Update';

$string['allowrename'] = 'Allow renames';
$string['allowdeletes'] = 'Allow deletes';
$string['allowsuspend'] = 'Allow suspending and activating of accounts';
$string['noemailduplicate'] = 'No duplicate email accounts';
$string['standardusername'] = 'Standardise usernames';

$string['yes'] = 'Yes';
$string['no'] = 'No';

$string['filedirectoryerror'] = 'File and directory Status: Error reading from remote directory.';
$string['filedirectoryerrornofile'] = 'File and directory Status: Filename is empty, please specify the filename.';
$string['filedirectoryerrornomatch'] = 'File and directory Status: No matching file found in the remote directory.';
$string['filedirectoryerrornotreadable'] = 'File and directory Status: Remote file not readable.';
$string['filedirectoryerrorlocal'] = 'File and directory Status: Local directory not readable.';
$string['filedirectorysuccesse'] = 'File and directory Status: File and directory exists and readable.';

$string['oneweek'] = '1 Week';
$string['twoweeks'] = '2 Weeks';
$string['threeweeks'] = '3 Weeks';
$string['fourweeks'] = '4 Weeks';
