<?php

include (__DIR__.'/../views/partials/header.php');
echo "<p>{$name}</p>";

?>

<h3><?php echo "{$event['family']} have reserved the cabin on {$event['date']}"; ?></h3>
  
  <p>&nbsp;</p>
  <?php 
if(strlen($event['family']) > 0) { 

    if ($event['owned']) { 
        echo '<form action="" method="post">
            <input name="id" type="hidden" value="'.$this->esc($event['id']).'">
            <input name="delete" type="submit" value=" click if not staying anymore ">
        </form>';
    } 
    
    
} else { ?>
  <p align='center'><h3>No Current Records</h3></p>
<?php 
} 
?>
  <p>&nbsp;</p>
  <p><a href="calendar.php">Return to Calendar</a> </p>
<?php
include (__DIR__.'/../views/partials/footer.php');
?>