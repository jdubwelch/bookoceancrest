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
}