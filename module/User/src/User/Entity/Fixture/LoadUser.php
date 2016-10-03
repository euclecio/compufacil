<?php

namespace User\Fixture;

use Doctrine\Common\DataFixtures\AbstractFixture,
    Doctrine\Common\Persistence\ObjectManager,
    Doctrine\Common\DataFixtures\OrderedFixtureInterface;

use User\Entity\User;

class LoadUser extends AbstractFixture implements OrderedFixtureInterface
{

    public function load(ObjectManager $manager)
    {
        $user1 = new User();
        $user1->setEmail("user1@example.com")
             ->setName("User One")
             ->setPassword(123456)
             ->setStatus(true);
        $manager->persist($user1);

        $user2 = new User();
        $user2->setEmail("user2@example.com")
             ->setName("User Two")
             ->setPassword(123456)
             ->setStatus(true);
        $manager->persist($user2);

        $user3 = new User();
        $user3->setEmail("user3@example.com")
             ->setName("User Three")
             ->setPassword(123456)
             ->setStatus(true);
        $manager->persist($user3);

        $user4 = new User();
        $user4->setEmail("user4@example.com")
             ->setName("User Four")
             ->setPassword(123456)
             ->setStatus(true);
        $manager->persist($user4);

        $user5 = new User();
        $user5->setEmail("user5@example.com")
             ->setName("User Five")
             ->setPassword(123456)
             ->setStatus(true);
        $manager->persist($user5);

        /* Adding friends to User1 */
        $user1->addFriend($user2)
              ->addFriend($user3)
              ->addFriend($user4)
              ->addFriend($user5);
        $manager->persist($user1);

        /* Adding friends to User2 */
        $user2->addFriend($user1)
              ->addFriend($user3)
              ->addFriend($user4);
        $manager->persist($user2);

        $manager->flush();
    }

    public function getOrder()
    {
        return 1;
    }
}
