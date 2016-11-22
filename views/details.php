<?php
include (__DIR__.'/../views/partials/header.php');
?>

<h1>Reserved</h1>
<h3><?php echo "<strong>{$event['family']}</strong> have reserved the cabin on {$event['date']}"; ?></h3>

  <p>&nbsp;</p>
  <?php
if(strlen($event['family']) > 0) {
    if ($event['owned']) {
        echo '<form action="" method="post">
            <input name="id" type="hidden" value="'.$this->esc($event['id']).'">
            <input name="delete" type="submit" class="btn btn-danger btn-large" value=" click if not staying anymore ">
        </form>';
    }
} else { ?>
  <p align='center'><h3>No Current Records</h3></p>
<?php
}
?>
  <p>&nbsp;</p>
  <p><a class="btn btn-block btn-primary" href="calendar">Return to Calendar</a> </p>
<?php
include (__DIR__.'/../views/partials/footer.php');
?>