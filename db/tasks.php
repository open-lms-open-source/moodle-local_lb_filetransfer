<?php

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
