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
}