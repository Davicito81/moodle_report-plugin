<?php
    require('config.php'); // Moodle's Konfigurationsdatei einbinden

    global $DB, $CFG;

    // Test-Datenbankeintrag
    $record = new stdClass();
    $record->taskname = 'Test-DB-Entry';
    $record->executiontime = time();
    $record->status = 'OK';
    $record->logdata = 'Manueller DB-Test';
    $record->timecreated = time();

    // Korrekte Tabelle mit Präfix ermitteln
    $table = $CFG->prefix . 'local_meinplugin_log';

    // Eintrag in die Datenbank schreiben
    $DB->insert_record($table, $record);

    echo "✅ Erfolgreich in die DB geschrieben!";
