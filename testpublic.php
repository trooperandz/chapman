<?php
//global $x;
$x = "a";
ff();
echo "x=", $x, "<br>";

$a = array("one" => 1, "two" => 2);

foreach ($a as $key => $v) {
	echo $key, $v, "<br>";
}

reset ($a);

$b = array(
		"rowone"=>array("field1"=>1, "field2"=>2),
		"rowtwo"=>array("field1"=>3, "field2"=>4)
		);
		
echo "test1:", "<br>";
foreach ($b as $row) {
	foreach ($row as $key=>$val) {
		echo $key, " ", $val, "<br>";
		}
	echo "<br>";
}
echo "test2:", "<br>";

foreach ($b as $val) {
	echo $val['field1'], "<br>";
	echo $val['field2'], "<br>";
		
	}
	
function ff()
{
	global $x;
	$x = "x is x";
}
?>