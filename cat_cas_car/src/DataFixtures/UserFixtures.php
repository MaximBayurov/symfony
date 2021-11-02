<?php

namespace App\DataFixtures;

use App\Entity\User;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends BaseFixtures
{
    private UserPasswordEncoderInterface $passwordEncoder;
    
    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }
    
    public function loadData(): void
    {
        $this->createMany(
            User::class,
            10,
            function (User $user) {
                $user
                    ->setEmail($this->faker->email)
                    ->setFirstName($this->faker->firstName)
                    ->setPassword($this->passwordEncoder->encodePassword($user, '12345'))
                    ->setIsActive(true)
                ;
                
                if ($this->faker->boolean(30)) {
                    $user->setIsActive(false);
                }
            }
        );
    }
}
