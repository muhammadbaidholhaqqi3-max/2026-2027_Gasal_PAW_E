//1.1
<?php
$fruits = array("Avocado", "Blueberry", "Cherry");

$fruits[] = "Durian";
$fruits[] = "Elderberry";
$fruits[] = "Fig";
$fruits[] = "Grape";
$fruits[] = "Honeydew";

echo "fruits = (\"" . implode('", "', $fruits) . "\")<br>";

$maxIndex = count($fruits) - 1;
echo "Nilai dengan indeks tertinggi: " . $fruits[$maxIndex];
echo'<br>';
?>

//1.2

<?php
$fruits = array("Avocado", "Blueberry", "Cherry", "Durian", "Elderberry", "Fig", "Grape", "Honeydew");

unset($fruits[1]);

$fruits = array_values($fruits);

echo "Data Blueberry dihapus.<br>";
echo "fruits = (\"" . implode('", "', $fruits) . "\")<br>";

$maxIndex = count($fruits) - 1;
echo "Nilai dengan indeks tertinggi: " . $fruits[$maxIndex];
?>