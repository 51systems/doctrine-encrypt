<?php

namespace DoctrineEncrypt\Tests\Subscribers;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\AnnotationRegistry;
use Doctrine\Common\EventManager;
use DoctrineEncrypt\Subscribers\DoctrineEncryptSubscriber;
use DoctrineEncrypt\Tests\Entity\User;
use DoctrineEncrypt\Tests\Tool\BaseTestCaseORM;
use DoctrineEncrypt\Tests\Tool\Rot13Encryptor;

class DoctrineEncryptSubscriberTest extends BaseTestCaseORM
{
    const USER = 'DoctrineEncrypt\Tests\Entity\User';

    /**
     * @var int
     */
    private $userId;

    public static function setUpBeforeClass()
    {
        AnnotationRegistry::registerLoader('class_exists');
    }

    public function setUp()
    {
        $evm = new EventManager();
        $evm->addEventSubscriber(new DoctrineEncryptSubscriber(
            new AnnotationReader(),
            new Rot13Encryptor()
        ));

        $this->getMockSqliteEntityManager($evm);
        $this->populate();
    }

    public function testReadUnencryptedPassword()
    {
        $stmt = $this->em->getConnection()->prepare('SELECT password FROM User');
        $stmt->execute();
        $result = $stmt->fetchColumn();

        $this->assertEquals('grfgCnffjbeq', $result);

        /** @var User $user */
        $user = $this->em->find(self::USER, $this->userId);

        $this->assertNotNull($user);
        $this->assertEquals('testPassword', $user->getPassword());
    }

    public function testDirtyEntity()
    {
        /** @var User $user */
        $user = $this->em->find(self::USER, $this->userId);

        $this->em->getUnitOfWork()->computeChangeSets();
        $this->assertFalse($this->em->getUnitOfWork()->isScheduledForUpdate($user));
    }

    public function testEmptyData()
    {
        {
            $user = new User();
            $user->setUsername('test');

            $this->em->persist($user);

            $this->assertEmpty($user->getPassword());
            $this->em->flush();
            $this->assertEmpty($user->getPassword());

            $this->em->clear();
        }

        {
            $stmt = $this->em->getConnection()->prepare('SELECT password FROM User u WHERE u.id=:userId');
            $stmt->execute(array(':userId' => $user->getId()));

            $result = $stmt->fetchColumn();

            $this->assertEmpty($result);
        }
    }

    public function testCommit()
    {
        $password = 'test2';

        $user = new User();
        $user->setUsername('test2');
        $user->setPassword($password);

        $this->em->persist($user);

        //Ensure the password is still plaintext after persist operation
        $this->assertEquals($password, $user->getPassword());

        $this->em->flush($user);

        //Ensure the password is still plaintext after flush
        $this->assertEquals($password, $user->getPassword());

        //Ensure we have a clean object
        $this->em->getUnitOfWork()->computeChangeSets();
        $this->assertFalse($this->em->getUnitOfWork()->isScheduledForUpdate($user));
    }

    /**
     * Get a list of used fixture classes
     *
     * @return array
     */
    protected function getUsedEntityFixtures()
    {
        return array(
            self::USER,
        );
    }

    private function populate()
    {
        $user = new User();
        $user->setUsername('testUsername');
        $user->setPassword('testPassword');

        $this->em->persist($user);
        $this->em->flush();
        $this->em->clear();
        $this->userId = $user->getId();
    }
}
