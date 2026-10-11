//2.1
<?php
$fruits = array("Avocado", "Blueberry", "Cherry");

for ($i = 1; $i <= 5; $i++) {
    $fruits[] = "Buah Tambahan " . $i;
}

$arrlength = count($fruits);
echo "Panjang array saat ini: " . $arrlength . "<br>";

for ($x = 0; $x < $arrlength; $x++) {
    echo $fruits[$x] . "<br>";
}
echo '<br>';
?>

//2.2
<?php
$vegies = array("Carrot", "Broccoli", "Spinach");
$arrlength = count($vegies);

for ($x = 0; $x < $arrlength; $x++) {
    echo $vegies[$x] . "<br>";
}
?>