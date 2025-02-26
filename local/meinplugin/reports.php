<?php
    require_once(__DIR__ . '/../../config.php');
    require_login();
    $context = context_system::instance();
    require_capability('moodle/site:config', $context);

    $PAGE->set_url(new moodle_url('/local/meinplugin/reports.php'));
    $PAGE->set_context($context);
    $PAGE->set_title(get_string('pluginname', 'local_meinplugin'));
    $PAGE->set_heading(get_string('pluginname', 'local_meinplugin'));

    echo $OUTPUT->header();
    echo "<h2>📊 Bericht über genutzte Plugins</h2>";

    // Datenbankabfrage der letzten 10 Einträge
    global $DB;
    $logs = $DB->get_records_sql("SELECT * FROM {local_meinplugin_log} ORDER BY timecreated DESC LIMIT 10");

    if (!$logs) {
        echo "<p>Keine Daten gefunden.</p>";
    } else {
        echo "<table border='1' cellpadding='5'>";
        echo "<tr><th>ID</th><th>Task</th><th>Zeitpunkt</th><th>Status</th><th>Details</th></tr>";
        foreach ($logs as $log) {
            echo "<tr>
                <td>{$log->id}</td>
                <td>{$log->taskname}</td>
                <td>" . date('Y-m-d H:i:s', $log->timecreated) . "</td>
                <td>{$log->status}</td>
                <td>{$log->logdata}</td>
            </tr>";
        }
        echo "</table>";
    }

    echo $OUTPUT->footer();
