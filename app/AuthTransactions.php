<?php namespace OceanCrest;

use Mlaphp\Request;

class AuthTransactions
{
    private $authGateway;
    private $request;
    private $errors = [];

    public function __construct(AuthGateway $authGateway, Request $request)
    {
        $this->authGateway = $authGateway;
        $this->request = $request;
    }

    public function getErrors()
    {
        return $this->errors;
    }

    public function attempt($credentials)
    {
        $this->errors = [];

        // Validate the email address.  
        if (empty($credentials['email'])) {
            $this->errors[] = 'You forgot to enter your email address.';
        }
        
        // Validate the password.
        if (empty($credentials['password'])) {
            $this->errors[] = 'You forgot to enter your password.';
        }

        if (! empty($this->errors)) {
            return false;
        }

        $user = $this->authGateway->attempt($credentials);

        if (! $user) {
            $this->errors[] = '<p><font color="red" size="+1">That was the wrong email or your account has not been activated.</font></p>'; 
            return false;
        }

        // A match was made, log the user in
        $this->request->session['name'] = $user->name;
        $this->request->session['user_id'] = $user->id;
        $this->request->session['side'] = $user->side;
                        
        return true;
    }
}