<?php

/**
 * Plugin administration pages are defined here.
 *
 * @package     local_learnbookfiletransfer
 * @category    admin
 * @copyright   2019 A K M Safat Shahin <safat@ecreators.com.au>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$tasks = array(
        array(
                'classname' => 'local_learnbookfiletransfer\task\filetransfer',
                'blocking' => 0,
                'minute' => '*',
                'hour' => '2',
                'day' => '*',
                'month' => '*',
                'dayofweek' => '*'
        )
);
