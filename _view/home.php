<?php
echo "<h1>Hello....." . $dao->title . "</h1>";
//if (isset($dao->result)) {
    echo "<p>" . $dao->result . "</p>";
//}
?>
<form method="post" action="">
    <label for="its_id">ITS ID (digits only):</label>
    <input type="number" id="its_id" name="its_id" required min="0">
    <button type="submit">Submit</button>
</form>