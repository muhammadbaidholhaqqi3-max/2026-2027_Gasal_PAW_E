<!DOCTYPE html>
<html lang="id">
<head><meta charset="UTF-8"><title>Validasi Server-side</title></head>
<body>
<?php
require 'validate.inc';
if (validateName($_POST, 'surname'))
    echo 'Data OK!';
else
    echo 'Data invalid!';
?>
</body>
</html>
