<?php # Script 13.6 - register.php

// Instantiate the AYAH object.
$ayah = new AYAH();

if (isset($request->post['submitted'])) { // Handle the form.
	
	// Use the AYAH object to get the score.
	$score = $ayah->scoreResult(); 

	// Check the score to determine what to do.
	if ($score)
	{
		// Add code to process the form.
        $userTransactions = $di->newInstance('OceanCrest\UserTransactions');

        $registered = $userTransactions->register([
            'name' => $request->post['name'],
            'side' => $request->post['side'],
            'email' => $request->post['email'],
            'password' => $request->post['password1'],
            'password_confirm' => $request->post['password2']
        ]);

        if ($registered) {
            echo '<h1>Thank you for registering! </h1>
            <p>You will receive and email once your account is activated.  It could take up to 24 hours to be activated.</p>';
            include ('./includes/footer.php'); // Include the HTML footer.
            exit(); 
        }

        echo '<p><font color="red" size="+1">'.implode('<br>', $userTransactions->getErrors()).'</font></p>';  

	}
	// you are NOT a human
	else
	{
        echo '<p><font color="red" size="+1">Human verification failed. Please try again.</font></p>';  
    }    
} // End of the main Submit conditional.

$response = $di->get('response');
$response->setView('register.php'); 
$response->setVars([
    'request' => $request,
    'page_title' => 'O C E A N  C R E S T >> REGISTER',
    'ayah' => $ayah
      
]);
$response->send();