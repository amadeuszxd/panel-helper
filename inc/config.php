<?php

	define ('PANEL_STAGE', 'tests');

	if ($_SERVER['HTTP_HOST'] != 'localhost') {
		define ('REQUIRE_SECURE_CONNECTION', true);
	}
