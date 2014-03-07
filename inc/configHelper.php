<?php
require_once './inc/config.php';

class ConfigHelper
{
	function __construct()
	{
	}

	public function __get($key)
	{
		switch ($key)
		{
			case 'panel_stage':
				return constant('PANEL_STAGE') == 'draw' ? 'draw' : 'results';
		}
		return null;
	}
}

$configHelper = new ConfigHelper();