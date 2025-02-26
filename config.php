<?php  // Moodle configuration file

  unset($CFG);
  global $CFG;
  // Füge das vor `require_once(__DIR__ . '/lib/setup.php');` in config.php hinzu:

  @error_reporting(E_ALL | E_STRICT);
  @ini_set('display_errors', '1');
  //@ini_set('log_errors', '1');
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

  $CFG = new stdClass();

  $CFG->debug = 32767; // E_ALL
  //$CFG->debug = (E_ALL | E_STRICT);
  $CFG->debugdisplay = 1;
  $CFG->dbtype    = 'mariadb';
  $CFG->dblibrary = 'native';
  $CFG->dbhost    = getenv('MOODLE_DB_HOST'); 
  $CFG->dbname    = getenv('MOODLE_DB_NAME'); 
  $CFG->dbuser    = getenv('MOODLE_DB_USER'); 
  $CFG->dbpass    = getenv('MOODLE_DB_PASS'); 
  $CFG->prefix    = 'mdl_';
  $CFG->dboptions = array (
    'dbpersist' => false,
    'dbport' => getenv('MOODLE_DB_PORT'),
    'dbsocket' => false,
    'dbhandlesoptions' => false,
    'dbcollation' => 'utf8mb4_general_ci',
    'connecttimeout' => null,
  );

  $CFG->wwwroot   = 'http://localhost/moodle';
  $CFG->dataroot  = 'C:\\xampp\\moodledata';
  $CFG->admin     = 'admin';
  $CFG->prefix = 'mdl_';
  $CFG->directorypermissions = 0777;

  # SMTP configurations from .env 
  $CFG->smtphosts  = getenv('SMTP_HOST');
  $CFG->smtpuser   = getenv('SMTP_USER');
  $CFG->smtppass   = getenv('SMTP_PASS');
  $CFG->smtpsecure = getenv('SMTP_SECURE');
  $CFG->smtpport   = getenv('SMTP_PORT');

  require_once(__DIR__ . '/lib/setup.php');

  // There is no php closing tag in this file,
  // it is intentional because it prevents trailing whitespace problems!
