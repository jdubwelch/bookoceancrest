<?php namespace OceanCrest;

use OceanCrest\UserGateway;

class UserTransactions 
{
    private $userGateway;

    private $errors = [];

    public function __construct(UserGateway $userGateway)
    {
        $this->userGateway = $userGateway;
    }

    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * Attempt to activated a user's account
     * @param  int $user_id 
     * @return boolean
     */
    public function activate($user_id)
    {
        $this->errors = [];

        $activated = $this->userGateway->activate($user_id);

        if (! $activated) {
            $this->errors[] = 'Your account could not be activated. Please re-check the link or contact the system administrator.';
            return false;
        }

        if (! $this->sendActivationNotification($user_id)) {
            $this->errors[] = 'Could not send activation notifivation';
            return false;
        }

        return true;
    }

    /**
     * Send a notification to the user that their account has been activated.
     * 
     * @param  int $user_id 
     * @return boolean
     */
    private function sendActivationNotification($user_id)
    {
        $email = $this->userGateway->email($user_id);
        
        $body = "You're bookoceancrest account has now been activated.\n\n
        Please login at http://www.bookoceancrest.com/login.php using your email and the password you set up.\n\n
        This was an automatically generated email, please do not reply.\n\n
        With questions about the website contact Jason Welch at jason@jwelchdesign.com\n\n";
        
        $subject = "Ocean Crest Acount";
        
        return mail($email, $subject, $body, 'From: info@bookoceancrest.com');
    }
    /**
     * Transaction for changing a user's password.
     * 
     * @param  int $user_id          
     * @param  string $password         
     * @param  string $password_confirm 
     * @return mixed                   
     */
    public function changePassword($user_id, $password, $password_confirm)
    {
        $this->errors = [];

        // Check for a new password and match against the confirmed password.
        if (preg_match('/^[[:alnum:]]{4,20}$/', stripslashes(trim($password)))) {
            if ($password == $password_confirm) {
                $password = trim($password);
            } else {
                $password = FALSE;
                $this->errors[] = 'Your password did not match the confirmed password.';
            }
        } else {
            $password = FALSE;
            $this->errors[] = 'Please enter a valid password. Numbers and letters only.';
        }
        
        if ($password) { // If everything's OK.
      
            $updated = $this->userGateway->updatePassword($user_id, $password);
    
            if ($updated) { // If it ran OK.
                return true;             
            } else { // If it did not run OK.
            
                // Send a message to the error log, if desired.
                $this->errors[] = 'Your password could not be changed due to a system error. We apologize for any inconvenience.'; 
            }       
    
        } else { // Failed the validation test.
            $this->errors[] = 'Please try again.';     
        }

        return false;
    }

    /**
     * Reset the password for a user.
     * 
     * @param  string $email
     * @return boolean
     */
    public function resetPassword($email)
    {
        $this->errors = [];

        // Validate the email address.
        if (empty($email)) {
            $this->errors[] = 'You forgot to enter your email address.';
            return false;
        } 

        // Check for the existence of that email address.
        $user_id = $this->userGateway->getUserByEmail($email);

        // No user exists
        if (! $user_id) {
            $this->errors[] = "Could not find a user with the email: {$email}. Please try again.";
            return false;
        }
        
        // Create a new, random password.
        $password = substr ( md5(uniqid(rand(),1)), 3, 10);

        // Make the query.
        $result = $this->userGateway->updatePassword($user_id, $password);

        if (! $result) {
            $this->errors[] = 'Your password could not be changed due to a system error. We apologize for any inconvenience.';
            return false;
        }
        
        // Send an email.
        $body = "Your password to log into bookoceancrest.com has been temporarily changed to '$password'. Please log in using this password and your username. At that time you may change your password to something more familiar.";
        mail ($email, 'Your temporary password.', $body, 'From: info@bookoceancrest.com');

        return true;
    }

    /**
     * The process of registering a user.
     * 
     * @param  array $input 
     * @return boolean
     */
    public function register($input)
    {
        $this->errors = [];

        // Check for a first name.
        if (empty($input['name'])) {
            $this->errors[] = 'Please enter your name.';
        }

        if ($input['side'] == '0') {
            $this->errors[] = 'Please your side of the family.';
        }

        // Check for an email address.
        if (! preg_match('/^[[:alnum:]][a-z0-9_\.\-]*@[a-z0-9\.\-]+\.[a-z]{2,4}$/', $input['email'])) {
            $this->errors[] = 'Please enter a valid email address.';
        }

        // Check for a password and match against the confirmed password.
        if (preg_match('/^[[:alnum:]]{4,20}$/', $input['password'])) {
            if ($input['password'] != $input['password_confirm']) {
                $this->errors[] = 'Your password did not match the confirmed password!';
            }
        } else {
            $this->errors[] = 'Please enter a valid password';
        }

        // Get outta here if the data is not good
        if (count($this->errors)) {
            return false;
        }

        // Only create people that don't have an account
        if (! $this->userGateway->uniqueEmail($input['email'])) { 
            $this->errors[] = 'The email address has already been registered. If you have forgotten your password, use the link to have your password sent to you.';
            return false;
        }

        // Add the user.
        $user_id = $this->userGateway->create(
            $input['name'], 
            $input['email'], 
            $input['side'],
            $input['password']
        );

        // Some kind of MySQL error in the SQL for some reason.
        if (! $user_id) {
            $this->errors[] = 'You could not be registered due to a system error. We apologize for any inconvenience.';
            return false;
        }

        // Notify the administrator so they know to activate.
        $body = "Name: {$input['name']}\n\nEmail: {$input['email']}\n\nSide: {$input['side']}\n\n";
        $body .= "http://www.bookoceancrest.com/activate.php?x=" . $user_id;
        mail('jw@jwelchdesign.com', 'bookoceancrest.com', $body, 'From: info@bookoceancrest.com');

        return true;
    }
}