<?php

/**
 * Find macaddress linked to a target interface.
 *
 * @ifaces array of interfaces to try.
 * @return macaddress of the first successfully detected interface.
 */
function findmacaddress($ifaces=array('eth0', 'en0', 'eth1', 'en1')) {
	if (stripos(PHP_OS, 'WIN') === 0) {
		$output = shell_exec("ipconfig /all");
		if (preg_match("/Physical[^:]+:(.*)/i", $output, $m)) {
			if (isset($m[1])) return str_replace('-', ':', trim($m[1]));
		}
	} else {
		foreach ($ifaces as $iface) {
			$output = shell_exec("/sbin/ifconfig $iface 2>&1");
			if (preg_match("/([0-9A-F]{2}[:-]){5}([0-9A-F]{2})/", strtoupper($output), $m)) {
				if (isset($m[0])) return trim($m[0]);
			}
		}
	}
	return null;
}
