//3.1
<?php
$height = array("Andy"=>"176", "Barry"=>"165", "Charlie"=>"170");

$height["David"] = "180";
$height["Ethan"] = "172";
$height["Frank"] = "168";
$height["George"] = "175";
$height["Harry"] = "182";

$keys = array_keys($height);
$values = array_values($height);
$formatted_height = [];
foreach($height as $k => $v) {
    $formatted_height[] = "\"$k\"=>\"$v\"";
}
echo "height = (" . implode(", ", $formatted_height) . ")<br>";

$lastKey = array_key_last($height);
echo "Nilai dengan indeks terakhir: " . $height[$lastKey] . "<br>";

unset($height["Barry"]);

$formatted_height = [];
foreach($height as $k => $v) {
    $formatted_height[] = "\"$k\"=>\"$v\"";
}
echo "height = (" . implode(", ", $formatted_height) . ")<br>";

$lastKeyAfter = array_key_last($height);
echo "Nilai dengan indeks terakhir setelah dihapus: " . $height[$lastKeyAfter];
echo '<br>';
?>

//3.2
<?php
$weight = array("Andy"=>"70", "Barry"=>"65", "Charlie"=>"75");

$values = array_values($weight);
$formatted_weight = [];
foreach($weight as $k => $v) {
    $formatted_weight[] = "\"$k\"=>\"$v\"";
}

echo "weight = (" . implode(", ", $formatted_weight) . ")<br>";
echo "Data kedua: " . $values[1];
?>