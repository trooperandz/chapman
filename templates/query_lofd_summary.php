<?php
/*  Consolidate computations  */
if ($totalros == 0) {$totalros = 1;}
$percent_LOF 		= number_format(($LOFrows/$totalros)*100,2)		;
$percent_SILOF 		= number_format(($SILOFrows/$totalros)*100,2)	;