<!-- filtr -->
<form id="search" method="post" action=""  style="margin: 1em">
	<div class="buttonset" style="float:left">
		<? foreach (dbProfile::$pv_grupy as $i=>$grupa) { ?>
			<? if ($grupa == 'w puli' || $grupa == 'robocza') { continue; } ?>
			<input id="grupa_<?=$i?>" type="checkbox" name="grupa[]" value="<?=$grupa?>"
					   <?=in_array($grupa, $tplData['prev']['grupa']) ? 'checked' : ''?>
				   >
			<label for="grupa_<?=$i?>"><?=$grupa?></label>
		<? } ?>
	</div>
	<div style="float:left; margin-left: 1em">
		<input type="submit" name="search" value="Zmień" />
	</div>
	<br clear="all" />
</form>