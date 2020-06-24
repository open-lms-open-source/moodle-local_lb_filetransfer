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
require_once($CFG->dirroot."/blocks/configurable_reports/locallib.php");
require($CFG->dirroot.'/local/lb_filetransfer/classes/lb_filetransfer_report_helper.php');


/**
 * Class lb_filetransfer_outgoingreport represents all the available outgoing report object.
 */
class lb_filetransfer_outgoingreport {

    /**
     * Creates local directory.
     * @return string
     */
    public function create_local_dir() {
        global $CFG;
        if (file_exists ($CFG->dataroot . '/temp/lb_filetransfer/outgoingreport')) {
            //Temporary folder already exists, using existing one.
            $tempdir = '/temp/lb_filetransfer/outgoingreport/';
            return $tempdir;
        }
        make_temp_directory('lb_filetransfer/outgoingreport/');
        //Created temp folder for the file.
        $tempdir = '/temp/lb_filetransfer/outgoingreport/';
        return $tempdir;
    }

    /**
     * exports the data to csv.
     * @return string
     */
    public function export_report($report, $destination_filename) {
        global $DB, $CFG;
        require_once($CFG->libdir . '/csvlib.class.php');

        $table = $report->table;
        $matrix = array();
        $filename = 'report';

        if (!empty($table->head)) {
            $countcols = count($table->head);
            $keys = array_keys($table->head);
            $lastkey = end($keys);
            foreach ($table->head as $key => $heading) {
                $matrix[0][$key] = str_replace("\n", ' ', htmlspecialchars_decode(strip_tags(nl2br($heading))));
            }
        }

        if (!empty($table->data)) {
            foreach ($table->data as $rkey => $row) {
                foreach ($row as $key => $item) {
                    $matrix[$rkey + 1][$key] = str_replace("\n", ' ', htmlspecialchars_decode(strip_tags(nl2br($item))));
                }
            }
        }

        $csvexport = new csv_export_writer();
        $csvexport->set_filename($filename);

        foreach ($matrix as $ri => $col) {
            $csvexport->add_data($col);
        }
        $tempdir = self::create_local_dir();
        $source_file = $csvexport->path;
        $destination = $CFG->dataroot . $tempdir . $destination_filename;
        if(copy($source_file, $destination)) {
            return $destination;
        }
        else {
            return false;
        }
    }

    /**
     * send outgoing report to sftp.
     * @return string
     * @throws dml_exception
     */
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
                $id = (int)$connection->configurablereportid;
//                $format = 'csv';
                if (!$report = $DB->get_record('block_configurable_reports', ['id' => $id])) {
                    //add event, configurable report does not exist anymore
                    continue;
                }
                $courseid = $report->courseid;
                if (!$course = $DB->get_record('course', ['id' => $courseid])) {
                    continue;
                }
                require_once($CFG->dirroot.'/blocks/configurable_reports/report.class.php');
                require_once($CFG->dirroot.'/blocks/configurable_reports/reports/'.$report->type.'/report.class.php');

                $reportclassname = 'report_'.$report->type;
                $reportclass = new $reportclassname($report);
                $reportclass->create_report();
                $export_file = self::export_report($reportclass->finalreport, $connection->filename);
                var_dump($export_file);
            }
        }
    }

}
