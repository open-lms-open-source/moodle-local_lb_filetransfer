<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.
/**
 * Plugin administration pages are defined here.
 *
 * @package     local_lb_filetransfer
 * @copyright   2020 eCreators PTY LTD
 * @author      2020 A K M Safat Shahin <safat@ecreators.com.au>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir.'/tablelib.php');

/**
 * Class outgoingreports_table.
 * An extension of your regular Moodle table.
 */
class outgoingreports_table extends table_sql {

    public $search = '';
    /**
     * outgoingreports_table constructor.
     * Sets the SQL for the table and the pagination.
     * @param $uniqueid
     * @throws coding_exception
     */
    public function __construct($uniqueid) {
        global $PAGE;
        parent::__construct($uniqueid);

        $columns = array('id', 'name', 'active', 'timecreated', 'timemodified', 'actions');
        $headers = array(
            get_string('id', 'local_lb_filetransfer'),
            get_string('name','local_lb_filetransfer'),
            get_string('status','local_lb_filetransfer'),
            get_string('timecreated', 'local_lb_filetransfer'),
            get_string('timemodified', 'local_lb_filetransfer'),
            get_string('actions','local_lb_filetransfer')
        );
        $this->no_sorting('actions');
        $this->is_collapsible = false;
        $this->define_columns($columns);
        $this->define_headers($headers);
        $fields = "id,
        name,
        active,
        FROM_UNIXTIME(timecreated, '%d/%m/%Y %H:%i:%s') AS timecreated,
        FROM_UNIXTIME(timemodified, '%d/%m/%Y %H:%i:%s') AS timemodified,
        timemodified as timemodified_raw,
        '' AS actions";
        $from = "{local_lb_filetr_reports}";
        $where = 'id > 0';
        $params = array();
        $this->set_sql($fields, $from, $where, $params);
        $this->set_count_sql("SELECT COUNT(id) FROM " . $from . " WHERE " . $where, $params);
        $this->define_baseurl($PAGE->url);
    }

    /**
     * @param $values
     * @return string
     * @throws moodle_exception
     */
    public function col_name($values) {
        global $CFG;
        $urlparams = array('id' => $values->id, 'sesskey' => sesskey());
        $editurl = new moodle_url('/local/lb_filetransfer/outgoingreport_form_page.php', $urlparams);
        // Make it an edit link.
        return '<a href = "' . $editurl . '">' . $values->name . '</a>';
    }

    /**
     * @param $values
     * @return string
     * @throws coding_exception
     */
    public function col_active($values) {
        $status = get_string('active', 'local_lb_filetransfer');
        $css = 'success';
        if (!$values->active) {
            $status = get_string('inactive', 'local_lb_filetransfer');
            $css = 'danger';
        }
        // Prettifies the return value.
        return '<div class = "text-' . $css . '"><i class = "fa fa-circle"></i>&nbsp;' . $status . '</div>';
    }

    /**
     * convert invalid to '-'
     * @param $values
     * @return string
     */
    public function col_timemodified($values) {
        return !empty($values->timemodified_raw) ? $values->timemodified : '-';
    }

    /**
     * @param $values
     * @return string Renderer template
     * @throws coding_exception
     * @throws moodle_exception
     */
    public function col_actions($values) {
        global $PAGE;

        $urlparams = array('id' => $values->id, 'sesskey' => sesskey());
        $editurl = new moodle_url('/local/lb_filetransfer/outgoingreport_form_page.php', $urlparams);
        $deleteurl = new moodle_url('/local/lb_filetransfer/outgoingreports.php', $urlparams + array('action' => 'delete'));
        // Decide twixt activate or deactivate.
        if ($values->active) {
            $toggleurl = new moodle_url('/local/lb_filetransfer/outgoingreports.php', $urlparams + array('action' => 'hide'));
            $togglename = get_string('deactivate', 'local_lb_filetransfer');
            $toggleicon = 'fa fa-eye';
        } else {
            $toggleurl = new moodle_url('/local/lb_filetransfer/outgoingreports.php', $urlparams + array('action' => 'show'));
            $togglename = get_string('activate', 'local_lb_filetransfer');
            $toggleicon = 'fa fa-eye-slash';
        }

        $renderer = $PAGE->get_renderer('local_lb_filetransfer');
        $params = array(
            'id' => $values->id,
            'buttons' => array(
                array(
                    'name' => get_string('edit'),
                    'icon' => 'fa fa-edit',
                    'url' => $editurl
                ),
                array(
                    'name' => get_string('delete'),
                    'icon' => 'fa fa-trash',
                    'url' => $deleteurl
                ),
                array(
                    'name' => $togglename,
                    'icon' => $toggleicon,
                    'url' => $toggleurl
                )
            )
        );

        return $renderer->render_action_icons($params);
    }

    /**
     * Export this data so it can be used as the context for a mustache template.
     *
     * @return string
     */
    public function export_for_template() {

        ob_start();
        $this->out(20, true);
        $tablehtml = ob_get_contents();
        ob_end_clean();

        return $tablehtml;
    }
}



