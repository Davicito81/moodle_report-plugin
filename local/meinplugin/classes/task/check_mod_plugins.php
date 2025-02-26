<?php
    namespace local_meinplugin\task;

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;
    
    // Security check
    defined('MOODLE_INTERNAL') || die(); 
    
    global $CFG;
    require_once($CFG->dirroot . '/config.php');
    require_once($CFG->dirroot . '/user/lib.php'); // Füge diese Zeile hinzu!
    require_once($CFG->dirroot . '/vendor/autoload.php');    
    
    /**
     * 
     * 
     */
    class check_mod_plugins extends \core\task\scheduled_task {

        /**
         * Get name of task
         * 
         * @return String
         */
        public function get_name(): string {
            return get_string('checkmodusage', 'local_meinplugin'); 
        }
    
        /**
         * Executes the task of course reports
         * 
         * @return void
         */
        public function execute(): void {
            mtrace("🚀 Task check_mod_plugins is starting...");
            global $DB; 

            // Time definition for requests from today to a year ago.
            $yearago = time() - 365 * 24 * 60 * 60;
    
            // Definition of relevant module
            $modNames = ['forum', 'assign', 'quiz', 'lesson', 'data', 'glossary', 'wiki', 'feedback', 'workshop', 'scorm'];
            list($insql, $inparams) = $DB->get_in_or_equal($modNames, SQL_PARAMS_QM);
    
            $sql = "SELECT cm.course, m.name, COUNT(cm.id) AS instances
                    FROM {course_modules} cm
                    JOIN {modules} m ON cm.module = m.id
                    JOIN {course} c ON cm.course = c.id
                    WHERE cm.added > ? 
                      AND c.visible = 1 
                      AND m.name $insql
                    GROUP BY cm.course, m.name
                    ORDER BY cm.course, m.name";
    
            $params = array_merge([$yearago], $inparams);
            $courses = $DB->get_records_sql($sql, $params);

            // Logging the results into the database
            //$this->log_results($courses);

            // Save report as CSV
            $this->generate_csv_report($courses);

            // Prepare all course reports as a String and send it via email response
            $this->generate_report($courses);
            
            mtrace("🎉 ask check_mod_plugins was successfully completed!");
        }

        /**
         * Store reports in a log table
         * 
         * @param Array
         * @return void
         */
        private function log_results($courses): void {
            global $DB, $CFG;

            foreach ($courses as $course) {
                $record = new \stdClass();
                $record->taskname = 'check_mod_plugins';
                $record->executiontime = time();
                $record->status = 'OK';
                $record->logdata = "Course ID: {$course->course}, Modul: {$course->name}, Instanzen: {$course->instances}";
                $record->timecreated = time();
                $DB->insert_record('mdl_local_meinplugin_log', $record);
                //$DB->insert_record("{$CFG->prefix}local_meinplugin_log", $record);
            }

            mtrace("✅ Results saved into the database.");
        }

        /**
         * Send an email to the right addressee that has already been stored.
         * 
         * @param string content of the report.
         * @return void
         */
        private function send_email(string $content): void {     
            global $CFG, $DB;      
            require_capability('moodle/site:config', \context_system::instance());

            // Set email address from Moodle configurations
            $recipientEmail = clean_param(get_config('local_meinplugin', 'email'), PARAM_EMAIL);
            if (empty($recipientEmail)) {
                debugging("⚠️ No recipient address configured for the report! Fallback to the main administrator as recipient...");

                // Fallback: Haupt-Administrator
                $admin = get_admin();
                if (!$admin || empty($admin->email)) {
                    debugging("❌ Admin doesn't found! The report cannot be sent.");
                    return;
                }
                $recipientEmail = $admin->email;                
            }
            
            // Get user object based on his email address.
            $recipient = $DB->get_record('user', ['email' => $recipientEmail], '*', IGNORE_MISSING);
           
            // Get Mailer instance for sending emails
            $mail = new PHPMailer(true);
            try {
                // SMTP configurations from config.php
                $mail->isSMTP();
                $mail->Host       = $CFG->smtphosts;
                $mail->SMTPAuth   = true;
                $mail->Username   = $CFG->smtpuser ;
                $mail->Password   = $CFG->smtppass;
                $mail->SMTPSecure = $CFG->smtpsecure;
                $mail->Port       = $CFG->smtpport;

                // Sender address
                $mail->setFrom($CFG->smtpuser, 'Moodle Admin');   

                // Use the recipient address of moodle user
                if ($recipient) 
                    $mail->addAddress($recipient->email, fullname($recipient)); 
                
                // Use pure email address, if moodle user dosen't exits!
                else{ 
                    debugging("❌ No User found with email: '$recipientEmail'! Use the pure email address instead...");
                    $mail->addAddress($recipientEmail); 
                }
                
                // Message
                $mail->isHTML(false);
                $mail->Subject = get_string('plugin_report_subject', 'local_meinplugin');
                $mail->Body    = get_string('plugin_report_body', 'local_meinplugin'). "\n\n" . $content;
                
                $mail->send();

                // Log event after successful email send
                /*$event = \local_meinplugin\event\report_sent::create([
                    'context' => \context_system::instance(),
                    'other' => ['email' => $recipientEmail]
                ]);
                $event->trigger();*/

                mtrace("✅ Roport successfully send by email!");
            
            // Throw an issue if something went wrong!
            } catch (Exception $e) {
                debugging("❌ Error sending email! {$mail->ErrorInfo}");
            }
        }

        /**
         * Save course reports as a CSV file.
         * 
         * @param Array course reports.
         * @return void
         */
        private function generate_report(Array $data): void {
            // Head of report.
            $report = "Course ID, Module Name, Instances\n";
            
            // Creates each reports in separate text lines.
            global $DB;
            foreach ($data as $d) 
                $report .= "{$d->course}, {$d->name}, {$d->instances}\n";   
           
            // Sent email with the report
            $this->send_email($report);
        }

        /**
         * Store reports as CSV-File
         * 
         * @param Array 
         * @return void
         */
        private function generate_csv_report($data): void {
            global $CFG;
            //$filepath = $CFG->dataroot . "/meinplugin_report.csv";
            $reportdir = make_request_directory('local_meinplugin');
            $filepath = $reportdir . "/report-plugin_report.csv";
        
            $file = fopen($filepath, 'w');
            fputcsv($file, ['Course ID', 'Modulname', 'Anzahl Instanzen']);
        
            foreach ($data as $d) 
                fputcsv($file, [$d->course, $d->name, $d->instances]);
        
            fclose($file);
            mtrace("📂 Report was stored succsessfully! $filepath");
        }
        
    }
