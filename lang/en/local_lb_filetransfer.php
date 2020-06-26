<?php

/**
 * Plugin strings are defined here.
 *
 * @package     local_lb_filetransfer
 * @category    string
 * @copyright   2020 A K M Safat Shahin <safat@ecreators.com.au>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$string['pluginname'] = 'Learnbook File Transfer';
$string['pluginname_description'] = 'Learnbook File Transfer plugin to automate user upload using CSV file from a remote repository.';

//task
$string['filetransfertask_upload'] = 'User upload';
$string['filetransfertask_report'] = 'Outgoing report';
$string['filetransfertask_auth_error'] = 'Can not authenticate using username & password for connection with id: {$a->id}.';
$string['filetransfertask_key_error'] = 'Can not authenticate using rsa key for connection with id: {$a->id}.';
$string['filetransfertask_configreport_error'] = 'Can not find the configurable report with id: {$a->id}.';
$string['filetransfertask_reportsend'] = 'Report with id: {$a->id} has been send to the remote directory.';
$string['filetransfertask_reporemailed'] = 'Report with id: {$a->id} has been emailed to: {$a->email}.';
$string['filetransfertask_filearchive'] = 'File cleanup and archiving has been done for report with id: {$a->id}.';
$string['filetransfertask_fileread_error'] = 'Can not read the file for connection with id: {$a->id}.';
$string['filetransfertask_nofile_error'] = 'Can not find the file for connection with id: {$a->id}.';
$string['filetransfertask_userupload'] = 'User upload is successfully finished for the user upload instance with id: {$a->id}.';
$string['filetransfertask_userupload_error'] = 'User upload for the instance with id: {$a->id} is not successful, please check the csv file.';
$string['filetransfertask_connection_error'] = 'User upload for the instance with id: {$a->id} is not successful, please check the connection settings.';
$string['filetransfertask_userfilearchive'] = 'File cleanup and archiving has been done for user upload instance with id: {$a->id}.';

//event
$string['filetransferstarted'] = 'File transfer process started.';
$string['filetransferevent'] = 'File processing';
$string['filetransfersuccess'] = 'File Transfer process and user upload completed successfully.';
$string['filetransfererror'] = 'Error, can not get file from remote directory';
$string['filetransfererrorcsv'] = 'User upload not successful, check the uploaded CSV.';

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
$string['connectioninfo'] = 'Connection info';

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
$string['archivefile'] = 'Archive file in moodledata';
$string['archiveperiod'] = 'Archive period';
$string['connectionid'] = 'Select connection';
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
$string['delete_processed'] = 'Delete remote file after processing';
$string['move_remotefile'] = 'Move remote file after processing';
$string['move_remotefile_directory'] = 'Path to after process directory (in remote location)';
$string['move_failed_files'] = 'Move unsuccessful file to a different directory';
$string['move_failed_files_directory'] = 'Path to unsuccessful files';
$string['getlatestfile'] = 'Get the last modified file from remote directory';

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
$string['email'] = 'Email addresses (comma separated, no space)';
$string['configurablereportid'] = 'Select report';
$string['emailpreference'] = 'Email preference (send email with)';
$string['emailpreference_report'] = 'Report file';
$string['emailpreference_log'] = 'Log information';
$string['emailpreference_both'] = 'Both report & log';
$string['outgoingreportpreference'] = 'Report preference (send the file to)';
$string['outgoingreportpreference_remote'] = 'Remote directory';
$string['outgoingreportpreference_email'] = 'Email';
$string['outgoingreportpreference_both'] = 'Both remote directory & email';

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

//outgoing report email
$string['outgoingreport_email_subject'] = 'Learnbook Filetransfer Report';
$string['outgoingreport_email_body'] = 'Hi, The report from Learnbook Filetransfer is ready for you. Please check the attached CSV.';
$string['outgoingreport_logemail_subject'] = 'Learnbook Filetransfer Log';
