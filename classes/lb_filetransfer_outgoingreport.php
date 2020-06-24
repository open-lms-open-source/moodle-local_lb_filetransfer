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
require($CFG->dirroot.'/local/lb_filetransfer/classes/lb_filetransfer_report_helper.php');

/**
 * Class lb_filetransfer_outgoingreport represents all the available outgoing report object.
 */
class lb_filetransfer_outgoingreport {

    public function send_outgoingreport() {
        global $CFG, $DB, $USER;
        $get_outgoingreport_instances = $DB->get_records_sql('SELECT *
                                                              FROM {local_lb_filetr_reports}
                                                              WHERE active = :active',
                                                              array('active' => 1));
        foreach($get_outgoingreport_instances as $key => $outgoingreport) {
            $connection = new lb_filetransfer_report_helper((int)$outgoingreport->id);
            if ($connection->test_connection()) {
                $emails = $connection->construct_email();

            }
        }
    }

}
