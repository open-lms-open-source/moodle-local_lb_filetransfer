<?php

/**
 * Plugin administration pages are defined here.
 *
 * @package     local_lb_filetransfer
 * @copyright   2020 A K M Safat Shahin <safat@ecreators.com.au>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

/**
 * Class connections_page represents a connections object.
 */

class connections_page {

    private $returnfields = 'id, name, active, timemodified, timecreated';

    public $active = 0;
    public $name = null;

    /**
     * connections_page constructor.
     * Builds object if $id provided.
     * @param null $id
     */
    public function __construct($id = null) {
        if (!empty($id)) {
            $this->load_connections_page($id);
        }
    }

    /**
     * Constructs the actual Connections object given either a $DB object or Moodle form data.
     * @param $connections
     */
    public function construct_connections_page($connections) {
        if (!empty($connections)) {
            $this->id = $connections->id;
            $this->name = $connections->name;
            $this->connectiontype = $connections->connectiontype;
            $this->hostname = $connections->hostname;
            $this->portnumber = $connections->portnumber;
            $this->username = $connections->username;
            $this->password = $connections->password;
            $this->usepublickey = $connections->usepublickey;
            $this->privatekey = $connections->privatekey;
            $this->active = $connections->active;
        }
    }

    /**
     * Let's reduce those page load times, people.
     * @throws dml_exception
     */
//    public function create_cache() {
//        // Sets up the cache object.
//        $cache = cache::make('local_lb_filetransfer', 'local_lb_filetransfer_cache');
//        // Delete previous frontpage cache, if it exists.
//        $cache->delete('cachehtml');
//        ob_start();
//        $context = context_system::instance();
//        // Make real URLs out of Moodle plugin URLs.
//        $html = file_rewrite_pluginfile_urls($this->html, 'pluginfile.php', $context->id,
//            'local_lb_filetransfer', 'customfrontpage', $this->id);
//        // Add styles.
//        if (!empty($this->css)) {
//            echo '<style type="text/css">' . $this->css . '</style>';
//        }
//        // Add HTML.
//        echo $html;
//        // Add JavaScript.
//        if (!empty($this->js)) {
//            echo '<script type="application/javascript">' . $this->js . '</script>';
//        }
//        $cachedata = ob_get_contents();
//        ob_end_clean();
//        // Engooden the new cache with delicious data.
//        $cache->set('cachehtml', $cachedata);
//    }

    /**
     * Delete the frontpage and, if it's active, delete the cache.
     * @return bool
     * @throws dml_exception
     */
    public function delete() {
        global $DB;
        if (!empty($this->id)) {
            return $DB->delete_records('local_lb_filetr_connections', array('id' => $this->id));
        }
        return false;
    }

    /**
     * Gets the active frontpage and loads it into the object.
     * @throws dml_exception
     */
    public function load_active_frontpage() {
        global $DB;
        $connections = $DB->get_record('local_lb_filetr_connections', array('active' => 1), $this->returnfields);
        $this->construct_connections_page($connections);
    }

    /**
     * Gets the specified frontpage and loads it into the object.
     * @param $id
     * @throws dml_exception
     */
    private function load_connections_page($id) {
        global $DB;
        $connections_page = $DB->get_record('local_lb_filetr_connections', array('id' => $id), $this->returnfields);
        $this->construct_connections_page($connections_page);
    }

    /**
     * Upsert function.
     * @return bool
     * @throws dml_exception
     */
    public function save() {
        global $DB;

        $savesuccess = false;
        if (!empty($this->id)) {
            $this->timemodified = time();
            $savesuccess = $DB->update_record('local_lb_filetr_connections', $this);
        } else {
            $this->timecreated = time();
            $this->id = $DB->insert_record('local_lb_filetr_connections', $this);
            if (!empty($this->id)) {
                $savesuccess = true;
            }
        }
        if ($savesuccess) {
//            // Put this in the cache if it's going to be the active frontpage.
//            if ($this->active) {
//                $this->create_cache();
//            }
            return true;
        }
        return false;
    }
}
