<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Importing district and regionNo</title>
  <script src="../js/getRegion_ajax.js" defer></script>

</head>
<body>
  <?php
  require '../php_for_ajax/districtRegionSelect.php';
  district("Kathmandu");
  regionNo("6");
  ?>
</body>
</html>