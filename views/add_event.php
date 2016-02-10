<?php
include (__DIR__.'/../views/partials/header.php');
echo "<p>{$name}</p>";
?>

<h3>Add a Calendar Event</h3>
<form name="form1" method="post" action="<?=$action?>">
<table width="450" border="0" cellpadding="3" cellspacing="0" id="addEvent">  
<tr>
    <td width="100" align="right">Date of Arrival:<input name="date" type="hidden" value="<?=$this->esc($day)?>"></td>
    <td align="left"><?=$arrival_date?></td>
  </tr>

  <tr>
    <td width="100" align="right">No. of days staying** </td>
    <td align="left"><select name="staying">
      <option value="1">1</option>
      <option value="2">2</option>
      <option value="3">3</option>
      <option value="4">4</option>
      <option value="5">5</option>
      <option value="6">6</option>
      <option value="7">7</option>
    </select></td>
  </tr>
  <tr>
    <td width="100" align="right"><input name="action" type="hidden" id="action" value="add"></td>
    <td align="center"><input type="submit" name="Submit" value="Add Event"></td>
  </tr>
</table>
<p>**note: the number of days does not carry over to the next month </p>
</form>
<?php
include (__DIR__.'/../views/partials/footer.php');
?>
