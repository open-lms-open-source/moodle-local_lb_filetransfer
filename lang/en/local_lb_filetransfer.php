<?php

/**
 * Plugin strings are defined here.
 *
 * @package     local_lb_filetransfer
 * @category    string
 * @copyright   2019 A K M Safat Shahin <safat@ecreators.com.au>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$string['pluginname'] = 'Learnbook File Transfer';
$string['pluginname_description'] = 'Learnbook File Transfer plugin to automate user upload using CSV file from a remote repository.';

//$string['host'] = 'Host name';
//$string['hostdesc'] = 'URL of the host.';
//
//$string['port'] = 'Port Number';
//$string['portdesc'] = 'Port number of the connection.';
//
//$string['username'] = 'Username';
//$string['usernamedesc'] = 'Enter the username of the connection.';
//
//$string['password'] = 'Password';
//$string['passworddesc'] = 'Enter the password of the connection.';
//
//$string['enableskey'] = 'Use public key authentication';
//$string['enableskeydesc'] = 'Enable or disable public key authentication.';
//
//$string['rsatoken'] = 'Private key';
//$string['rsatokendesc'] = 'Enter private key to authenticate via SFTP.';
//
//$string['remotedir'] = 'Path to file';
//$string['remotedirdesc'] = 'Location of the remote directory, e.g. /directory/';
//
//$string['masterfile'] = 'File name';
//$string['masterfiledesc'] = 'Name of the file to upload.';
//
//$string['emailenable'] = 'Email log report';
//$string['emailenabledesc'] = 'Enable or disable the email to get the log report.';
//
//$string['emaillog'] = 'Receiver emails';
//$string['emaillogdesc'] = 'Enter the emails to send the log report, e.g. example@email.com,example2@email.com.';
//
//$string['connectionerror'] = 'Connection Status: Error connecting to the server, please check your connection information';
//$string['connectionerrorpassword'] = 'Connection Status: Error connecting to the server, please check your username and password.';
//$string['connectionerrorrsa'] = 'Connection Status: Error connecting to the server, please check your username and RSA key.';
//$string['connectionerrornohost'] = 'Connection Status: Hostname is empty, please specify the hostname';
//$string['connectionsuccess'] = 'Connection Status: Successfully connected to the server.';
//
//$string['filetransfertask'] = 'Learnbook File trasfer integration.';
//$string['filearchiveenable'] = 'Enable processed file archiving.';
//$string['filearchiveenabledesc'] = 'Files will be archived in the lb_filetransfer_backup directory upto 4 weeks.';
//$string['filearchiveperiod'] = 'Select your file archive period';
//$string['filearchiveperioddesc'] = 'Selected period will be used to retain processed CSV files.';
//
//$string['connectionsetting'] = 'Connection Settings';
//$string['filesetting'] = 'File and Directory Settings';
//$string['uploadsetting'] = 'User Upload Settings';
//
//$string['uutype'] = 'Upload type';
//$string['uuoptype_addinc'] = 'Add all, append number to usernames if needed';
//$string['uuoptype_addnew'] = 'Add new only, skip existing users';
//$string['uuoptype_addupdate'] = 'Add new and update existing users';
//$string['uuoptype_update'] = 'Update existing users only';
//
//$string['uupasswordnew'] = 'New user password';
//$string['infilefield'] = 'Field required in file';
//$string['createpasswordifneeded'] = 'Create password if needed and send via email';
//
//$string['uuupdatetype'] = 'Existing user details';
//$string['nochanges'] = 'No changes';
//$string['uuupdatefromfile'] = 'Override with file';
//$string['uuupdateall'] = 'Override with file and defaults';
//$string['uuupdatemissing'] = 'Fill in missing from file and defaults';
//
//$string['uupasswordold'] = 'Existing user password';
//$string['nochanges'] = 'No changes';
//$string['update'] = 'Update';
//
//$string['allowrename'] = 'Allow renames';
//$string['allowdeletes'] = 'Allow deletes';
//$string['allowsuspend'] = 'Allow suspending and activating of accounts';
//$string['noemailduplicate'] = 'No duplicate email accounts';
//$string['standardusername'] = 'Standardise usernames';
//
//$string['yes'] = 'Yes';
//$string['no'] = 'No';
//
//$string['filedirectoryerror'] = 'File and directory Status: Error reading from remote directory.';
//$string['filedirectoryerrornofile'] = 'File and directory Status: Filename is empty, please specify the filename.';
//$string['filedirectoryerrornomatch'] = 'File and directory Status: No matching file found in the remote directory.';
//$string['filedirectoryerrornotreadable'] = 'File and directory Status: Remote file not readable.';
//$string['filedirectoryerrorlocal'] = 'File and directory Status: Local directory not readable.';
//$string['filedirectorysuccesse'] = 'File and directory Status: File and directory exists and readable.';
//
//$string['oneweek'] = '1 Week';
//$string['twoweeks'] = '2 Weeks';
//$string['threeweeks'] = '3 Weeks';
//$string['fourweeks'] = '4 Weeks';
//
//$string['filetransferstarted'] = 'File transfer process started.';
//$string['filetransferevent'] = 'User upload process';
//$string['filetransfersuccess'] = 'File Transfer process and user upload completed successfully.';
//$string['filetransfererror'] = 'Error, can not get file from remote directory';
//$string['filetransfererrorcsv'] = 'User upload not successful, check the uploaded CSV.';

//general table
$string['go-back'] = 'Go back';
$string['createnew'] = 'Create new';
$string['id'] = 'Id';
$string['name'] = 'Name';
$string['status'] = 'Status';
$string['timecreated'] = 'Time Created';
$string['timemodified'] = 'Time Modified';
$string['actions'] = 'Actions';
$string['active'] = 'Active';
$string['inactive'] = 'Inactive';
$string['activate'] = 'Activate';
$string['deactivate'] = 'Deactivate';

//index-page
$string['config_connections'] = 'Configure Connections';
$string['config_useruploads'] = 'Configure User Uploads';
$string['config_outgoingreports'] = 'Configure Outgoing Reports';

//connections page
$string['delete_connection'] = 'Delete connection';
$string['delete_connection_confirmation'] = 'Are you sure you want to delete this connection: {$a->name}.';
$string['connection_deleted'] = 'Connection: {$a->name} deleted.';
$string['connection_delete_failed'] = 'Could not delete connection: {$a->name}, please remove associate data before deleting connection.';
$string['connection_active'] = 'Connection: {$a->name} is active.';
$string['connection_active_error'] = 'Can not activate connection: {$a->name}.';
$string['connection_deactive'] = 'Connection: {$a->name} is deactivated.';
$string['connection_deactive_error'] = 'Can not deactivate connection: {$a->name}, please remove associate data before making changes.';
$string['connection_saved'] = 'Connection saved successfully';
$string['connection_save_error'] = 'Error saving the connection';

//connections form
$string['name'] = 'Connection name';
$string['connectiontype'] = 'Connection type';
$string['connection_sftp'] = 'SFTP';
$string['connection_ftp'] = 'FTP';
$string['hostname'] = 'Hostname';
$string['portnumber'] = 'Port number';
$string['username'] = 'Username';
$string['password'] = 'Password';
$string['usepublickey'] = 'Use public key';
$string['privatekey'] = 'Private key';
$string['yes'] = 'Yes';
$string['no'] = 'No';
$string['number_only'] = 'Numeric characters only';
$string['maximum_character_255'] = 'Maximum 255 characters';
$string['maximum_character_1024'] = 'Maximum 1024 characters';

//useruploads form
$string['pathtofile'] = 'Path to file';
$string['filename'] = 'File name';
$string['twoweeks'] = '2 Weeks';
$string['fourweeks'] = '4 Weeks';
$string['archivefile'] = 'Archive file';
$string['archiveperiod'] = 'Archive period';
$string['connectionid'] = 'Select connection';

//useruploads page
$string['delete_userupload'] = 'Delete user upload instance';
$string['delete_userupload_confirmation'] = 'Are you sure you want to delete this user upload instance: {$a->name}.';
$string['userupload_deleted'] = 'User upload instance: {$a->name} deleted.';
$string['userupload_delete_failed'] = 'Could not delete user upload instance: {$a->name}.';
$string['userupload_active'] = 'User upload instance: {$a->name} is active.';
$string['userupload_active_error'] = 'Can not activate user upload instance: {$a->name}.';
$string['userupload_deactive'] = 'User upload instance: {$a->name} is deactivated.';
$string['userupload_deactive_error'] = 'Can not deactivate user upload instance: {$a->name}.';
$string['userupload_saved'] = 'User upload information saved successfully.';
$string['userupload_save_error'] = 'Error saving user upload information.';

//outgoing report form
$string['email'] = 'Email report (comma separated, no space)';
$string['configurablereportid'] = 'Select report';

//outgoing reports page
$string['delete_outgoingreport'] = 'Delete outgoing report instance';
$string['delete_outgoingreport_confirmation'] = 'Are you sure you want to delete this outgoing report instance: {$a->name}.';
$string['outgoingreport_deleted'] = 'Outgoing report instance: {$a->name} deleted.';
$string['outgoingreport_delete_failed'] = 'Could not delete outgoing report instance: {$a->name}.';
$string['outgoingreport_active'] = 'Outgoing report instance: {$a->name} is active.';
$string['outgoingreport_active_error'] = 'Can not activate outgoing report instance: {$a->name}.';
$string['outgoingreport_deactive'] = 'Outgoing report instance: {$a->name} is deactivated.';
$string['outgoingreport_deactive_error'] = 'Can not deactivate outgoing report instance: {$a->name}.';
$string['outgoingreport_saved'] = 'Outgoing report information saved successfully.';
$string['outgoingreport_save_error'] = 'Error saving outgoing report information.';
