<?php
    defined('MOODLE_INTERNAL') || die();

    $tasks = array(
        array(
            'classname' => 'local_meinplugin\\task\\check_mod_plugins',
            'blocking' => 0,
            'minute' => '*/5',
            'hour' => '*',
            'day' => '*',
            'dayofweek' => '*',
            'month' => '*'
        )
    );
