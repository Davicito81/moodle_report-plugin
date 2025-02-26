<?php
    define('CLI_SCRIPT', true); // WICHTIG: CLI-Modus aktivieren
    
    require_once(__DIR__.'/config.php');
    require_once($CFG->libdir.'/moodlelib.php');

    //$admin = \core_user::get_user(2); // Admin-User abrufen
    $admin = new \stdClass();
    $admin->id = 7;
    $admin->email = 'admin@example.com'; // Deine Admin-Mail hier eintragen
    $admin->firstname = 'Admin';
    $admin->lastname = 'User';
    $admin->firstnamephonetic = '';
    $admin->lastnamephonetic = '';
    $admin->middlename = '';
    $admin->alternatename = '';

    $subject = 'Moodle Test-Mail';
    $message = 'Dies ist eine Test-E-Mail aus Moodle.';

    // ✅ Richtiges `From:` setzen
    $mailfrom = new stdClass();
    $mailfrom->email = 'portal@f-muhsal.de';  // ✅ MUSS mit `smtpuser` übereinstimmen!
    $mailfrom->firstname = 'Moodle';
    $mailfrom->lastname = 'Admin';

    // Senden der E-Mail
    if (email_to_user($admin, $mailfrom, $subject, $message)) {
        echo "✅ Test-E-Mail erfolgreich gesendet!\n";
    } else {
        echo "❌ Fehler beim E-Mail-Versand!\n";
    }

    
  
    