<?php


namespace App\Fixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

/**
 * Class UsersFixtures
 */
class UsersFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i < 20; $i++) {
            $user = new User();
            $user->setFirstName('firstName '.$i)
                ->setLastName('lastName '.$i)
                ->setCountry('France')
                ->setNumber('0102030405')
                ->setCountryCode('FR')
                ->setInternationalNumber('0033102030405')
                ;

            $manager->persist($user);
        }

        $manager->flush();
    }
}
