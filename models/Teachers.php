<?php
class Teachers extends Users
{


    public function __construct($username, $email, $password, $member, $spcode)
    {
        parent::__construct($username, $email, $password, $member, $spcode);
    }
}
?>