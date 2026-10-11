//4.1
<?php
$height = array("Andy"=>"176", "Barry"=>"165", "Charlie"=>"170");

$height["David"] = "180";
$height["Ethan"] = "172";
$height["Frank"] = "168";
$height["George"] = "175";
$height["Harry"] = "182";

$formatted_height = [];
foreach($height as $k => $v) {
    $formatted_height[] = "\"$k\"=>\"$v\"";
}
echo "height = (" . implode(", ", $formatted_height) . ")<br>";

foreach($height as $name => $cm) {
    echo "$name is $cm cm tall.<br>";
}
echo '<br>';
?>

//4.2
<?php
$weight = array("Andy"=>"70", "Barry"=>"65", "Charlie"=>"75");

// Menampilkan bentuk array
$formatted_weight = [];
foreach($weight as $k => $v) {
    $formatted_weight[] = "\"$k\"=>\"$v\"";
}
echo "weight = (" . implode(", ", $formatted_weight) . ")<br>";

// Mengubah array asosiatif menjadi numerik agar bisa diakses dengan perulangan for standar
$keys = array_keys($weight);
$values = array_values($weight);
$length = count($weight);

for($i = 0; $i < $length; $i++) {
    echo $keys[$i] . " is " . $values[$i] . " kg.<br>";
}
?>