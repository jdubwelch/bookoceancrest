<?php
include (__DIR__.'/../views/partials/header.php');
?>
<h1>Register</h1>
<form action="register.php" method="post">
    <fieldset>
    
    <p><b>Name:</b> 
    <input name="name" type="text" id="name" value="<?php if (isset($request->post['name'])) echo $request->post['name']; ?>" size="15" maxlength="15" />
    <small>(for example: Jason &amp; Deena) </small></p>
    
    <p><b>Side:</b> 
      <select name="side" id="side">
        <option value="0">-----------</option>
        <option value="Schumacher">Schumacher</option>
        <option value="Welch">Welch</option>
      </select>
    </p>
    
    <p><b>Email Address:</b> <input type="text" name="email" size="40" maxlength="40" value="<?php if (isset($request->post['email'])) echo $request->post['email']; ?>" /> </p>
        
    <p><b>Password:</b> <input type="password" name="password1" size="20" maxlength="20" /> <small>Use only letters and numbers. Must be between 4 and 20 characters long.</small></p>
    
    <p><b>Confirm Password:</b> <input type="password" name="password2" size="20" maxlength="20" /></p>
    </fieldset>
    
    <?php
        // Use the AYAH object to get the HTML code needed to
        // load and run the PlayThru.
        echo $ayah->getPublisherHTML();
    ?>
        
    <div align="center"><input type="submit" name="submit" value="Register" /></div>
    <input type="hidden" name="submitted" value="TRUE" />

</form>
<?php
include (__DIR__.'/../views/partials/footer.php');
?>