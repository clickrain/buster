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
		}

		$file = $_SERVER['DOCUMENT_ROOT'] . $href;
		$hash = sha1_file($file);

		$lastpos = strrpos($href, '.');

		$left = substr($href, 0, $lastpos + 1);
		$right = substr($href, $lastpos);
		$result = $left . $hash . $right;
		$this->return_data = $result;
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

<?php
		$buffer = ob_get_contents();
		ob_end_clean();
		return $buffer;
	}
}
