<?
	/* @var $pv_controller ModuleController */

	require_once ('./inc/dbConnect.php');
	require_once ('./inc/db/profile.php');
	$dbProfile = new dbProfile();

	//
	// Przetwarzanie danych
	//
	$tplData = array();
	// wypełnienie pól wyboru
	$dbProfile->pf_getStats($tplData['dzielnice'], 'dzielnice');
	$dbProfile->pf_getStats($tplData['wyksztalcenie'], 'wyksztalcenie');

	$tplData['prev'] = array();
	$pv_choices = array('dzielnica', 'plec', 'wyksztalcenie', 'dzieci', 'wiek_od', 'wiek_do');
	foreach ($pv_choices as $choice)
	{
		$tplData['prev'][$choice] = (!empty($_POST[$choice])) ? $_POST[$choice] : '';
	}
	if (empty($tplData['prev']['wyksztalcenie']))
	{
		$tplData['prev']['wyksztalcenie'] = array();
	}

	if (!empty($_POST['search']))
	{
		$pv_allow = array('dzielnica', 'plec', 'dzieci');
		$pv_ograniczenia = array();
		foreach ($pv_allow as $name)
		{
			if (!empty($_POST[$name]))
			{
				$pv_ograniczenia[$name] = $_POST[$name];
			}
		}
		// extra transform
		if (!empty($_POST['wyksztalcenie']))
		{
			$pv_ograniczenia['wyksztalcenie'] = array('IN', $_POST['wyksztalcenie']);
		}
		if (!empty($_POST['wiek_od']) || !empty($_POST['wiek_do']))
		{
			$rok = intval(date('Y'));
			if (!empty($_POST['wiek_od'])) $pv_ograniczenia['rok'] = array('<=', $rok - intval($_POST['wiek_od']));
			if (!empty($_POST['wiek_do'])) $pv_ograniczenia['rok '] = array('>=', $rok - intval($_POST['wiek_do']));
		}
		// get
		$dbProfile->pf_getRecords($tplData['profiles'], $pv_ograniczenia, array('ankieta_id', 'dzielnica', 'plec', 'wiek', 'wyksztalcenie', 'dzieci'));
	}
	// wiek -> rok

	$pv_controller->tpl->file = 'search.tpl.php';
	$pv_controller->tpl->data = $tplData;

	//
	// Wyświetlanie template
	//
	$pv_controller->tpl->render();
?>