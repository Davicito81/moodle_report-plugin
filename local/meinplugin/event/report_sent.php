<?php
    namespace local_meinplugin\event;

    // Security check
    defined('MOODLE_INTERNAL') || die();

    /**
     * Event for when a report is successfully sent via email.
     */
    class report_sent extends \core\event\base {
        
        /**
         * Initialize event properties.
         * 
         * @return void
         */
        protected function init(): void {
            $this->data['crud'] = 'r'; // Read action (report was sent)
            $this->data['edulevel'] = self::LEVEL_OTHER; // Not directly related to teaching
        }
        
        /**
         * Get the event name.
         * 
         * @return String
         */
        public static function get_name(): string {
            return get_string('event_report_sent', 'local_meinplugin');
        }

        /**
         * Get event description.
         * 
         * @return String
         */
        public function get_description(): string {
            return "The report was successfully sent to {$this->other['email']}.";
        }
    }
