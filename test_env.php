<?php
     define('CLI_SCRIPT', true); // WICHTIG: CLI-Modus aktivieren
    $envFile = __DIR__ . '/.env';
    if (file_exists($envFile)) {
        $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            if (strpos(trim($line), '#') === 0) continue; // Kommentare ignorieren
            list($name, $value) = explode('=', $line, 2);
            $_ENV[trim($name)] = trim($value);
            putenv(trim($name) . "=" . trim($value));
        }
    }

    #echo "MOODLE_DB_HOST: " . getenv('MOODLE_DB_HOST') . PHP_EOL;
    #echo "SMTP_HOST: " . getenv('SMTP_HOST') . PHP_EOL;
    #echo "SMTP_USER: " . getenv('SMTP_USER') . PHP_EOL;
    #echo "SMTP_PORT: " . getenv('SMTP_PORT') . PHP_EOL;

    echo $_ENV['MOODLE_DB_HOST'];
    echo $_ENV['MOODLE_DB_NAME']; 
    echo $_ENV['MOODLE_DB_USER']; 
    echo $_ENV['MOODLE_DB_PASS']; 