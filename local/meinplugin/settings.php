<?php
	// Security check
	defined('MOODLE_INTERNAL') || die(); 

	// Check, if user has the right permissions to manage the Site-Administration!
	if ($hassiteconfig) { 
		$settings = new admin_settingpage('local_meinplugin_settings', get_string('settings', 'local_meinplugin'));
		$ADMIN->add('localplugins', $settings);

		// Configuration field for the email address
		$settings->add(new admin_setting_configtext(
			'local_meinplugin/email', 
			get_string('email', 'local_meinplugin'), 
			get_string('configemail', 'local_meinplugin'), 
			'', // default (empty)
			//PARAM_NOTAGS // Doesn't permit HTML tags
			PARAM_EMAIL // **Right PARAM-Typ for E-Mail**
		));
	}
