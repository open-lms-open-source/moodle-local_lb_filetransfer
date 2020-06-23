<?php

/**
 * Plugin administration pages are defined here.
 *
 * @package     local_lb_filetransfer
 * @copyright   2020 A K M Safat Shahin <safat@ecreators.com.au>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

if ($hassiteconfig) {
    $ADMIN->add("localplugins", new admin_externalpage('local_lb_filetransfer',
        get_string('pluginname', 'local_lb_filetransfer'), $CFG->wwwroot . "/local/lb_filetransfer/index.php"));
}

//use local_lb_filetransfer\settings\setting_statictext;

//if ($hassiteconfig) {
//    require_once(__DIR__.'/classes/settings/setting_statictext.php');
//    require_once($CFG->dirroot.'/local/lb_filetransfer/filetransfer.php');
//
//    global $OUTPUT;
//    $settings = new admin_settingpage('local_lb_filetransfer', new lang_string('pluginname', 'local_lb_filetransfer'));
//    $ADMIN->add('localplugins', $settings);
//    $settings->add(new admin_setting_heading('local_lb_filetransfer', '', get_string('pluginname_description', 'local_lb_filetransfer')));
//    $connectionStatus = connectionStatus();
//    $connectionTest = $OUTPUT->notification(get_string('connectionerror', 'local_lb_filetransfer'), 'notifyproblem');
//    switch ($connectionStatus){
//        case 1:
//            $connectionTest = $OUTPUT->notification(get_string('connectionerrorpassword', 'local_lb_filetransfer'), 'notifyproblem');
//            break;
//        case 2:
//            $connectionTest = $OUTPUT->notification(get_string('connectionerrorrsa', 'local_lb_filetransfer'), 'notifyproblem');
//            break;
//        case 3:
//            $connectionTest = $OUTPUT->notification(get_string('connectionerrornohost', 'local_lb_filetransfer'), 'notifyproblem');
//            break;
//        case 4:
//            $connectionTest = $OUTPUT->notification(get_string('connectionsuccess', 'local_lb_filetransfer'), 'notifysuccess');
//            break;
//        default:
//            $connectionTest = $OUTPUT->notification(get_string('connectionerror', 'local_lb_filetransfer'), 'notifyproblem');
//            break;
//    }
//
//    $name = 'local_lb_filetransfer/connectiontest';
//    $setting = new setting_statictext($name, $connectionTest);
//    $settings->add($setting);

//    $name = 'local_lb_filetransfer/connectionsetting';
//    $visiblename = get_string('connectionsetting','local_lb_filetransfer');
//    $settings->add(new admin_setting_heading($name, $visiblename, ''));
//
//    $name = 'local_lb_filetransfer/host';
//    $visiblename = get_string('host','local_lb_filetransfer');
//    $description = get_string('hostdesc','local_lb_filetransfer');
//    $settings->add(new admin_setting_configtext($name, $visiblename, $description, ''));
//
//    $name = 'local_lb_filetransfer/port';
//    $visiblename = get_string('port','local_lb_filetransfer');
//    $description = get_string('portdesc','local_lb_filetransfer');
//    $settings->add(new admin_setting_configtext($name, $visiblename, $description, '22'));
//
//    $name = 'local_lb_filetransfer/username';
//    $visiblename = get_string('username','local_lb_filetransfer');
//    $description = get_string('usernamedesc','local_lb_filetransfer');
//    $settings->add(new admin_setting_configtext($name, $visiblename, $description, ''));
//
//    $name = 'local_lb_filetransfer/password';
//    $visiblename = get_string('password','local_lb_filetransfer');
//    $description = get_string('passworddesc','local_lb_filetransfer');
//    $settings->add(new admin_setting_configpasswordunmask($name, $visiblename, $description, ''));
//
//    $name = 'local_lb_filetransfer/enablekey';
//    $visiblename = get_string('enableskey','local_lb_filetransfer');
//    $description = get_string('enableskeydesc','local_lb_filetransfer');
//    $settings->add(new admin_setting_configcheckbox($name, $visiblename, $description, 0));
//
//    $name = 'local_lb_filetransfer/rsatoken';
//    $visiblename = get_string('rsatoken','local_lb_filetransfer');
//    $description = get_string('rsatokendesc','local_lb_filetransfer');
//    $settings->add(new admin_setting_configtext($name, $visiblename, $description, ''));

//    $fileDirectoryStatus = testFileDirectory();
//    $fileDirectoryTest = $OUTPUT->notification(get_string('filedirectoryerror', 'local_lb_filetransfer'), 'notifyproblem');
//    switch ($fileDirectoryStatus) {
//        case 1:
//            $fileDirectoryTest = $OUTPUT->notification(get_string('filedirectoryerrornofile', 'local_lb_filetransfer'), 'notifyproblem');
//            break;
//        case 2:
//            $fileDirectoryTest = $OUTPUT->notification(get_string('filedirectoryerrornomatch', 'local_lb_filetransfer'), 'notifyproblem');
//            break;
//        case 3:
//            $fileDirectoryTest = $OUTPUT->notification(get_string('filedirectoryerrornotreadable', 'local_lb_filetransfer'), 'notifyproblem');
//            break;
//        case 4:
//            $fileDirectoryTest = $OUTPUT->notification(get_string('filedirectorysuccesse', 'local_lb_filetransfer'), 'notifysuccess');
//            break;
//        default:
//            $fileDirectoryTest = $OUTPUT->notification(get_string('filedirectoryerror', 'local_lb_filetransfer'), 'notifyproblem');
//            break;
//    }
//
//    $name = 'local_lb_filetransfer/filedirectorytest';
//    $setting = new setting_statictext($name, $fileDirectoryTest);
//    $settings->add($setting);

//    $name = 'local_lb_filetransfer/filesetting';
//    $visiblename = get_string('filesetting','local_lb_filetransfer');
//    $settings->add(new admin_setting_heading($name, $visiblename, ''));
//
//    $name = 'local_lb_filetransfer/remotedir';
//    $visiblename = get_string('remotedir','local_lb_filetransfer');
//    $description = get_string('remotedirdesc','local_lb_filetransfer');
//    $settings->add(new admin_setting_configtext($name, $visiblename, $description, ''));
//
//    $name = 'local_lb_filetransfer/masterfile';
//    $visiblename = get_string('masterfile','local_lb_filetransfer');
//    $description = get_string('masterfiledesc','local_lb_filetransfer');
//    $settings->add(new admin_setting_configtext($name, $visiblename, $description, ''));
//
//    $name = 'local_lb_filetransfer/filearchive';
//    $visiblename = get_string('filearchiveenable','local_lb_filetransfer');
//    $description = get_string('filearchiveenabledesc','local_lb_filetransfer');
//    $settings->add(new admin_setting_configcheckbox($name, $visiblename, $description, 0));
//
//    $oneWeek = get_string('oneweek','local_lb_filetransfer');
//    $twoWeeks = get_string('twoweeks','local_lb_filetransfer');
//    $threeWeeks = get_string('threeweeks','local_lb_filetransfer');
//    $fourWeeks = get_string('fourweeks','local_lb_filetransfer');
//    $dropdown = array($oneWeek,$twoWeeks,$threeWeeks,$fourWeeks);
//    $name = 'local_lb_filetransfer/filearchiveperiod';
//    $visiblename = get_string('filearchiveperiod','local_lb_filetransfer');
//    $description = get_string('filearchiveperioddesc','local_lb_filetransfer');
//    $settings->add(new admin_setting_configselect($name, $visiblename, $description, 0,$dropdown));
//
//    $name = 'local_lb_filetransfer/uploadsetting';
//    $visiblename = get_string('uploadsetting','local_lb_filetransfer');
//    $settings->add(new admin_setting_heading($name, $visiblename, ''));

//    $uu_user_addnew = get_string('uuoptype_addnew', 'local_lb_filetransfer');
//    $uu_user_addinc = get_string('uuoptype_addinc', 'local_lb_filetransfer');
//    $uu_user_add_update = get_string('uuoptype_addupdate', 'local_lb_filetransfer');
//    $uu_user_update = get_string('uuoptype_update', 'local_lb_filetransfer');
//    $dropdown = array($uu_user_addnew,$uu_user_addinc,$uu_user_add_update,$uu_user_update);
//
//    $name = 'local_lb_filetransfer/uutype';
//    $visiblename = get_string('uutype','local_lb_filetransfer');
//    $settings->add(new admin_setting_configselect($name, $visiblename, '', 2,$dropdown));

//    $infilefield = get_string('infilefield', 'local_lb_filetransfer');
//    $createpasswordifneeded = get_string('createpasswordifneeded', 'local_lb_filetransfer');
//    $dropdown = array($infilefield,$createpasswordifneeded);
//
//    $name = 'local_lb_filetransfer/uupasswordnew';
//    $visiblename = get_string('uupasswordnew','local_lb_filetransfer');
//    $settings->add(new admin_setting_configselect($name, $visiblename, '', 1,$dropdown));

//    $uu_update_nochanges = get_string('nochanges', 'local_lb_filetransfer');
//    $uu_update_fileoverride = get_string('uuupdatefromfile', 'local_lb_filetransfer');
//    $uu_update_alloverride  = get_string('uuupdateall', 'local_lb_filetransfer');
//    $uu_update_missing = get_string('uuupdatemissing', 'local_lb_filetransfer');
//    $dropdown = array($uu_update_nochanges,$uu_update_fileoverride,$uu_update_alloverride,$uu_update_missing);
//
//    $name = 'local_lb_filetransfer/uuupdatetype';
//    $visiblename = get_string('uuupdatetype','local_lb_filetransfer');
//    $settings->add(new admin_setting_configselect($name, $visiblename, '', 2,$dropdown));

//    $nochanges  = get_string('nochanges', 'local_lb_filetransfer');
//    $update = get_string('update', 'local_lb_filetransfer');
//    $dropdown = array($nochanges,$update);
//
//    $name = 'local_lb_filetransfer/uupasswordold';
//    $visiblename = get_string('uupasswordold','local_lb_filetransfer');
//    $settings->add(new admin_setting_configselect($name, $visiblename, '', 0,$dropdown));

//    $yes = get_string('yes', 'local_lb_filetransfer');
//    $no = get_string('no', 'local_lb_filetransfer');
//    $dropdown = array($no,$yes);
//
//    $name = 'local_lb_filetransfer/allowrename';
//    $visiblename = get_string('allowrename','local_lb_filetransfer');
//    $settings->add(new admin_setting_configselect($name, $visiblename, '', 1,$dropdown));

//    $yes = get_string('yes', 'local_lb_filetransfer');
//    $no = get_string('no', 'local_lb_filetransfer');
//    $dropdown = array($no,$yes);
//
//    $name = 'local_lb_filetransfer/allowdeletes';
//    $visiblename = get_string('allowdeletes','local_lb_filetransfer');
//    $settings->add(new admin_setting_configselect($name, $visiblename, '', 1,$dropdown));

//    $yes = get_string('yes', 'local_lb_filetransfer');
//    $no = get_string('no', 'local_lb_filetransfer');
//    $dropdown = array($no,$yes);
//
//    $name = 'local_lb_filetransfer/allowsuspend';
//    $visiblename = get_string('allowsuspend','local_lb_filetransfer');
//    $settings->add(new admin_setting_configselect($name, $visiblename, '', 1,$dropdown));

//    $yes = get_string('yes', 'local_lb_filetransfer');
//    $no = get_string('no', 'local_lb_filetransfer');
//    $dropdown = array($no,$yes);
//
//    $name = 'local_lb_filetransfer/noemailduplicate';
//    $visiblename = get_string('noemailduplicate','local_lb_filetransfer');
//    $settings->add(new admin_setting_configselect($name, $visiblename, '', 1,$dropdown));

//    $yes = get_string('yes', 'local_lb_filetransfer');
//    $no = get_string('no', 'local_lb_filetransfer');
//    $dropdown = array($no,$yes);
//
//    $name = 'local_lb_filetransfer/standardusername';
//    $visiblename = get_string('standardusername','local_lb_filetransfer');
//    $settings->add(new admin_setting_configselect($name, $visiblename, '', 1,$dropdown));

//}
