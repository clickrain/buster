<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$plugin_info = array(
	'pi_name'        => 'Buster',
	'pi_version'     => '0.1.0',
	'pi_author'      => 'Click Rain',
	'pi_author_url'  => 'http://clickrain.com/',
	'pi_description' => 'Buster - Bust up some caches',
	'pi_usage'       => Buster::usage()
	);


class Buster
{
	var $return_data = '';
	function Buster()
	{
		Buster::__construct();
	}

	function __construct() {
		$this->EE =& get_instance();

		$href = $this->EE->TMPL->fetch_param('href', '');
		if ($href === '') {
			$href = $this->EE->TMPL->fetch_param('src', '');
		}
		if ($href === '') {
			$this->return_data = '';
			return;
		}

		// If the plugin is explicitely diabled, then don't do anything. Why
		// would anybody do this? For development or debugging reasons,
		// mostly.
		if (!$this->is_enabled()) {
			$this->return_data = $href;
			return;
		}

		$file = $_SERVER['DOCUMENT_ROOT'] . $href;
		$hash = sha1_file($file);

		$lastpos = strrpos($href, '.');

		$left = substr($href, 0, $lastpos + 1);
		$right = substr($href, $lastpos);
		$result = $left . $hash . $right;
		$this->return_data = $result;
	}

	function is_enabled() {

		// First, determine if the user set this variable at all. If the user
		// has not set the config variable, we want to enable Buster by
		// default. For one, because that's what the user would expect, and
		// for two, because that is backwards compatible with previous
		// versions of Buster.
		//
		// Now, here's the tricky part. EE->config->item('...') will return
		// false if the variable has not been set. It will also return false
		// when the variable has been set to false. For Buster, those two
		// things are *completely* different.
		//
		// CodeIgniter's config class (which this is) does not have a way to
		// determine if a config value exists or not. So, we have to descend
		// into it's config private variable array to see if it's been set.
		if (!isset($this->EE->config->config['buster_enabled'])) {
			// The config value was not set. So, enable Buster by default.
			return true;
		}

		$value = $this->EE->config->item('buster_enabled');
		if ($value === false) {
			// Now that we know the variable has been set, then if we get a
			// false, we know it's because the user set the variable to false.
			// And that clearly means disable Buster.
			return false;
		}

		// The value wasn't false. So, check if it's a string that indicates
		// that the user wants the plugin disabled.
		switch (strtolower($value)) {
			case '0':
			case 'n':
			case 'no':
			case 'false':
			case 'disabled':
				return false;
		}

		// The user hasn't explicitely requested that the plugin be disabled.
		// So, for *any* other value, we assume the user wants it enabled.
		// Basically, err on the side of enabling the plugin.
		return true;
	}

	/**
	 * Usage
	 *
	 * This function describes how the plugin is used.
	 *
	 * @access	public
	 * @return	string
	 */

	//  Make sure and use output buffering

	function usage()
	{
		ob_start();
?>

{exp:buster href="/assets/css/style.css"}
=> /assets/css/style.8c9fcf8364b72ec65c233629375c241763bf245b.css

<link rel="stylesheet" href="{exp:buster href="/assets/css/style.css"}">
=> <link rel="stylesheet" href="/assets/css/style.8c9fcf8364b72ec65c233629375c241763bf245b.css">

{exp:buster href="/assets/js/script.js"}
=> /assets/js/script.8c9fcf8364b72ec65c233629375c241763bf245b.js

<script src="{exp:buster src="/assets/js/script.js"}"></script>
=> <script src="/assets/js/script.8c9fcf8364b72ec65c233629375c241763bf245b.js"></script>

etc.


To turn off the plugin, set $config['buster_enabled'] = FALSE in the config file.

<?php
		$buffer = ob_get_contents();
		ob_end_clean();
		return $buffer;
	}
}
