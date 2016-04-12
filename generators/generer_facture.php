<?php
	ob_start();
		require '../models/facture.php';
	$content = ob_get_clean();
	
	$pdf = new \mikehaertl\wkhtmlto\Pdf();
	$pdf->addPage($content);
	
	echo $pdf->send();
?>