<?php
/**
 * Logger for regiring visits.
 */
class VisitLogger
{
	const FILE_PATH = './.vists.log';
	const SEPARATOR = "\t";
	const MAX_REQUEST_VALUE = 100;

	/**
	 * Get current base origin.
	 * @param array $s $_SERVER or euqivalent.
	 * @param boolean $use_forwarded_host
	 * @return string
	 */
	private static function baseUrl($use_forwarded_host = false )
	{
		$s = $_SERVER;
		$ssl      = ( ! empty( $s['HTTPS'] ) && $s['HTTPS'] == 'on' );
		$sp       = strtolower( $s['SERVER_PROTOCOL'] );
		$protocol = substr( $sp, 0, strpos( $sp, '/' ) ) . ( ( $ssl ) ? 's' : '' );
		$port     = $s['SERVER_PORT'];
		$port     = ( ( ! $ssl && $port=='80' ) || ( $ssl && $port=='443' ) ) ? '' : ':'.$port;
		$host     = ( $use_forwarded_host && isset( $s['HTTP_X_FORWARDED_HOST'] ) )
			? $s['HTTP_X_FORWARDED_HOST']
			: ( isset( $s['HTTP_HOST'] ) ? $s['HTTP_HOST'] : null )
		;
		$host     = isset( $host ) ? $host : $s['SERVER_NAME'] . $port;
		return $protocol . '://' . $host;
	}

	/**
	 * Encode GET/POST request table.
	 * @param array $table
	 */
	private static function encodeRequest($table)
	{
		if (empty($table)) {
			return "";
		}
		$queryString = "?";
		foreach ($table as $key => $value)
		{
			if (strlen($value) > self::MAX_REQUEST_VALUE) {
				$value = substr($value, 0, self::MAX_REQUEST_VALUE - 1) . "…";
			}
			$queryString .= rawurlencode($key)."=".rawurlencode($value)."&";
		}
		return rtrim($queryString, "&");
	}

	/**
	 * Gather visit data.
	 * @return array
	 */
	public static function gatherData()
	{
		$data = array();
		// dt
		$data[] = date('c');
		// IP
		$data[] = $_SERVER['REMOTE_ADDR'];
		// user
		$data[] = isset($_SERVER['PHP_AUTH_USER']) ? $_SERVER['PHP_AUTH_USER'] : '-';
		// base url
		$data[] = self::baseUrl();
		// script
		$data[] = $_SERVER["SCRIPT_NAME"];
		// GET/POST
		$data[] = 'GET'.self::encodeRequest($_GET);
		$data[] = 'POST'.self::encodeRequest($_POST);
		return $data;
	}

	/**
	 * Register the visit.
	 */
	public static function register()
	{
		$info = implode(self::SEPARATOR, self::gatherData());
		file_put_contents(self::FILE_PATH, $info."\n", FILE_APPEND);
	}
}