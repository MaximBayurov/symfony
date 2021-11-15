<?php

namespace App\Form\Model;

use App\Validator\UniqueUser;
use App\Validator\RegistrationSpam;
use Symfony\Component\Validator\Constraints as Assert;

class UserRegistrationFormModel
{
    /**
     * @Assert\Email()
     * @Assert\NotBlank()
     * @RegistrationSpam()
     * @UniqueUser()
     */
    private $email;
    /**
     * @Assert\NotBlank(message="Пароль не указан")
     * @Assert\Length(min=6, minMessage="Пароль должен содержать не меньше 6 символов")
     */
    private $plainPassword;
    /**
     * @Assert\IsTrue(message="Вы должны согласиться с условиями")
     */
    private $agreeTerms;
    private $firstName;
    
    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }
    
    /**
     * @param mixed $email
     */
    public function setEmail($email): void
    {
        $this->email = $email;
    }
    
    /**
     * @return mixed
     */
    public function getPlainPassword()
    {
        return $this->plainPassword;
    }
    
    /**
     * @param mixed $plainPassword
     */
    public function setPlainPassword($plainPassword): void
    {
        $this->plainPassword = $plainPassword;
    }
    
    /**
     * @return mixed
     */
    public function getAgreeTerms()
    {
        return $this->agreeTerms;
    }
    
    /**
     * @param mixed $agreeTerms
     */
    public function setAgreeTerms($agreeTerms): void
    {
        $this->agreeTerms = $agreeTerms;
    }
    
    /**
     * @return mixed
     */
    public function getFirstName()
    {
        return $this->firstName;
    }
    
    /**
     * @param mixed $firstName
     */
    public function setFirstName($firstName): void
    {
        $this->firstName = $firstName;
    }
}