<?php

$arr1 = array("A");
array_push($arr1, "B");
echo "Array awal: (\"A\")<br>";
echo "Hasil array_push: " . implode(" ", $arr1) . "<br><br>";

$arr2_1 = array("A", "B");
$arr2_2 = array("C");
$merged = array_merge($arr2_1, $arr2_2);
echo "Array awal: (\"A\", \"B\") digabung dengan (\"C\")<br>";
echo "Hasil array_merge: " . implode(" ", $merged) . "<br><br>";

// 3. array_values()
$arr3 = array("x"=>1, "y"=>2);
$values = array_values($arr3);
echo "Array awal: (\"x\"=>1, \"y\"=>2)<br>";
echo "Hasil array_values: " . implode(" ", $values) . "<br><br>";

$arr4 = array("A", "B", "C");
$pos = array_search("B", $arr4);
echo "Mencari \"B\" pada array: (\"A\", \"B\", \"C\")<br>";
echo "Hasil array_search: " . $pos . "<br><br>";

$arr5 = array(0, 1, false, 2, "", 3, "array");
$filtered = array_filter($arr5);
echo "Array awal: (0, 1, false, 2, \"\", 3, \"array\")<br>";
echo "Hasil array_filter: " . implode(" ", $filtered) . "<br><br>";

$arr6 = array(3, 1, 2);
echo "Array awal: (3, 1, 2)<br>";
$s1 = $arr6; sort($s1);
echo "Hasil sort: " . implode(" ", $s1) . "<br>";
$s2 = $arr6; rsort($s2);
echo "Hasil rsort: " . implode(" ", $s2) . "<br><br>";

$assoc = array("Peter"=>35, "Ben"=>37, "Joe"=>43);

$a1 = $assoc; asort($a1);
echo "Hasil asort: ";
foreach($a1 as $k=>$v) { echo "$k=>$v "; }
echo "<br>";

$a2 = $assoc; ksort($a2);
echo "Hasil ksort: ";
foreach($a2 as $k=>$v) { echo "$k=>$v "; }
echo "<br>";

$a3 = $assoc; arsort($a3);
echo "Hasil arsort: ";
foreach($a3 as $k=>$v) { echo "$k=>$v "; }
echo "<br>";

$a4 = $assoc; krsort($a4);
echo "Hasil krsort: ";
foreach($a4 as $k=>$v) { echo "$k=>$v "; }
?>