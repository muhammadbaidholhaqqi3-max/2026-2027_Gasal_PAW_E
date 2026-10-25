<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Register</title>
<style>
 body{font-family:Verdana,sans-serif;font-size:13px;margin:20px}
 fieldset{background:#d9d9ff;width:420px}
 .field{margin:4px 0;display:flex;align-items:flex-start}
 .field label{width:120px;text-align:right;padding-right:8px}
</style>
</head>
<body>
<?php
 $errors = array();
 if (isset($_POST['surname']))
 {
    require 'validate.inc';
    validateName($errors, $_POST, 'surname');
    if ($errors)
    {
        echo '<h1>Invalid, correct the following errors:</h1>';
        foreach ($errors as $field => $error)
            echo "$field $error</br>";

        // tampilkan kembali form
        include 'form.inc';
    }
    else
    {
        echo 'Form submitted successfully with no errors';

        // Menampilkan data yang dikirimkan (array asosiatif $_POST)
        echo '<h3>Posted data: </h3>';
        foreach ($_POST as $key => $value)
            echo "($key) => (" . htmlspecialchars($value) . ")<br/>";
    }
 }
 else
    // tampilkan kembali form
    include 'form.inc';
?>
</body>
</html>
