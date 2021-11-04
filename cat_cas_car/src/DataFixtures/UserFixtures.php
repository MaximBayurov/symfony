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
        $this->create(
            User::class,
            function (User $user) {
                $user
                    ->setEmail('admin@symfony.skillbox')
                    ->setFirstName('Add Man')
                    ->setPassword($this->passwordEncoder->encodePassword($user, '12345'))
                    ->setIsActive(true)
                    ->setRoles(["ROLE_ADMIN"])
                ;
            }
        );
        
        $this->create(
            User::class,
            function (User $user) {
                $user
                    ->setEmail('api@symfony.skillbox')
                    ->setFirstName('Api Man')
                    ->setPassword($this->passwordEncoder->encodePassword($user, '12345'))
                    ->setIsActive(true)
                    ->setRoles(["ROLE_API"])
                ;
            }
        );
        
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
