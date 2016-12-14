<?php
include (__DIR__.'/../views/partials/header.php');
?>

<h1>Reserve the Cabin</h1>
<form name="form1" method="post" action="/add_event.php" class="form-horizontal">
    <input name="action" type="hidden" id="action" value="add">
    <div class="form-group">
        <label for="arrivalDate" class="col-sm-2 control-label">Arrival Date</label>
        <div class="col-sm-2">
            <input type="text" name="date" class="form-control" id="arrivalDate" value="<?=$arrival_date?>" disabled>
            <input type="hidden" name="date" value="<?=$this->esc($day)?>" />
        </div>
    </div>
    <div class="form-group">
        <label for="totalDays" class="col-sm-2 control-label">No. of Days Staying</label>
        <div class="col-sm-2">
            <select name="staying" class="form-control" id="totalDays">
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
                <option value="5">5</option>
                <option value="6">6</option>
                <option value="7">7</option>
            </select>
            <span id="helpBlock" class="help-block">
                Note: The number of days does not carry over to the next month.
            </span>
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
            <button type="submit" class="btn btn-primary">Make Reservation</button>
        </div>
    </div>
</form>

<?php
include (__DIR__.'/../views/partials/footer.php');
?>
