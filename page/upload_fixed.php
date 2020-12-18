<?php
function check_file_content($filename) {
    return true;
}
function validate_data($xml) {
  if (!$xml) return false;
  foreach ($xml -> student as $student) {
    if (!preg_match("/^[0-9]{1,5}$/", $student -> id)) return false;
    if (!preg_match("/^[A-Z][a-z]{1,7}$/", $student -> name)) return false;
    if (!preg_match("/^[0-9]{4,4}$/", $student -> year)) return false;
    if (!preg_match("/^[A-Z]{2,5}$/", $student -> school)) return false;
  }
  return true;
}

if (isset($_POST["submit"])) {
    $upload_err = '';
    $file = pathinfo($_FILES['fileToUpload']['name']);
    // call $file['filename'] and $file['extension'] to get filename and extension of uploaded file
  
    $whilelist_extension = array('xml' => 1);
    // Check filename
    if (!preg_match("/^[a-zA-Z0-9]{1,20}\.[a-zA-Z0-9]{1,7}$/i", basename($_FILES['fileToUpload']['name']))) $upload_err = 'Invalid file name. Filename contain no more than 20 characters and only letters, numbers are allowed!';
    else if (!array_key_exists($file['extension'], $whilelist_extension)) $upload_err = 'Only xml are allowed!';
    else if (!check_file_content($_FILES["fileToUpload"]["tmp_name"])) $upload_err = 'Malicious file content!';
    else if ($_FILES['fileToUpload']['size'] > 5000000) $upload_err = 'File size need to be no more than 5MB'; 
  
    if (empty($upload_err)) {
      // Simply disable the ability to load external entities to prevent XSS attacks
      // libxml_disable_entity_loader(false);

      $xml = simplexml_load_file($_FILES['fileToUpload']['tmp_name']);
      if (validate_data($xml)) {
        // Try to parse info to a table
        echo "<table>";
        echo "<tr>";
        echo "<th>Id</th>";
        echo "<th>Name</th>";
        echo "<th>Year</th>";
        echo "<th>School</th>";
        echo "</tr>";
        foreach ($xml -> student as $student) {
          echo "<tr>";
          echo "<td>". $student -> id ."</td>";
          echo "<td>". $student -> name . "</td>";
          echo "<td>". $student -> year . "</td>";
          echo "<td>". $student -> school ."</td>";
          echo "</tr>";
        }
        echo "</table>";
      }
      else $upload_err = "Something wrong. Please try again.";
    }
    
    if (!empty($upload_err)) echo "<p>$upload_err</p>";
  }
  
?>

<h2>This is upload page!</h2>
<form class="form-inline" method="post" enctype="multipart/form-data">
  <div class="form-group">
    <label for="file">Choose XML file to upload</label>
    <input type="file" class="form-control" name="fileToUpload">
  </div>
  <button type="submit" class="btn btn-primary" name="submit">Upload</button>
</form>
