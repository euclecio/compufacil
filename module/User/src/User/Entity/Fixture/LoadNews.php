<?php

namespace User\Fixture;

use Doctrine\Common\DataFixtures\AbstractFixture,
    Doctrine\Common\Persistence\ObjectManager,
    Doctrine\Common\DataFixtures\OrderedFixtureInterface;

use User\Entity\News;

class LoadNews extends AbstractFixture implements OrderedFixtureInterface
{

    public function load(ObjectManager $manager)
    {
        $user1 = $manager->getReference('User\Entity\User', 1);
        $user2 = $manager->getReference('User\Entity\User', 2);
        $user3 = $manager->getReference('User\Entity\User', 3);
        $user4 = $manager->getReference('User\Entity\User', 4);
        $user5 = $manager->getReference('User\Entity\User', 5);

        /* User1's news */
        $news = new News();
        $news->setMessage('User1: Lorem ipsum dolor sit asimet')
             ->setUser($user1);
        $manager->persist($news);

        /* User2's news */
        $news = new News();
        $news->setMessage('User2: Lorem ipsum dolor sit asimet')
             ->setUser($user2);
        $manager->persist($news);

        $news = new News();
        $news->setMessage('User2: Lorem ipsum dolor sit asimet')
             ->setUser($user2);
        $manager->persist($news);

        $news = new News();
        $news->setMessage('User2: Lorem ipsum dolor sit asimet')
             ->setUser($user2);
        $manager->persist($news);

        /* User3's news */
        $news = new News();
        $news->setMessage('User3: Lorem ipsum dolor sit asimet')
             ->setUser($user3);
        $manager->persist($news);

        $news = new News();
        $news->setMessage('User3: Lorem ipsum dolor sit asimet')
             ->setUser($user3);
        $manager->persist($news);

        /* User4's news */
        $news = new News();
        $news->setMessage('User4: Lorem ipsum dolor sit asimet')
             ->setUser($user4);
        $manager->persist($news);

        $manager->flush();
    }

    public function getOrder()
    {
        return 2;
    }
}
