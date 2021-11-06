<?php

namespace App\DataFixtures;

use App\Entity\ApiToken;
use App\Entity\User;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use function Clue\StreamFilter\fun;

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
                
                $this->manager->persist(new ApiToken($user));
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
                
                for ($i = 0; $i < 3; $i++) {
                    $this->manager->persist(new ApiToken($user));
                }
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
                
                $this->manager->persist(new ApiToken($user));
            }
        );
    }
}
