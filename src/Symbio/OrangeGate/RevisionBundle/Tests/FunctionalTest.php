<?php
/**
 * Created by PhpStorm.
 * User: jiri.bazant
 * Date: 14.05.15
 * Time: 14:22
 */
namespace Symbio\OrangeGate\RevisionBundle\Tests;


use Doctrine\Common\EventManager;
use Doctrine\ORM\Mapping AS ORM;
use SimpleThings\EntityAudit\AuditConfiguration;
use SimpleThings\EntityAudit\AuditManager;
use Symbio\OrangeGate\RevisionBundle\EventListener\RelationAudit;


class FunctionalTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $em = null;

    /**
     * @var AuditManager
     */
    private $auditManager = null;

    /**
     * @var RelationAudit
     */
    private $revisionListener = null;

    /**
     * @var array
     */
    private $models = array(
        'Symbio\OrangeGate\RevisionBundle\Tests\Model1',
        'Symbio\OrangeGate\RevisionBundle\Tests\Model2',
        'Symbio\OrangeGate\RevisionBundle\Tests\RelationMN',
    );


    private function setUpEM(EventManager $evm) {
        $reader = new \Doctrine\Common\Annotations\AnnotationReader();
        $driver = new \Doctrine\ORM\Mapping\Driver\AnnotationDriver($reader);
        $config = new \Doctrine\ORM\Configuration();
        $config->setMetadataCacheImpl(new \Doctrine\Common\Cache\ArrayCache);
        $config->setQueryCacheImpl(new \Doctrine\Common\Cache\ArrayCache);
        $config->setProxyDir(sys_get_temp_dir());
        $config->setProxyNamespace('Symbio\OrangeGate\RevisionBundle\Tests\Proxies');
        $config->setMetadataDriverImpl($driver);

        $this->em = \Doctrine\ORM\EntityManager::create(
            array(
                'driver' => 'pdo_sqlite',
                'memory' => true,
            ),
            $config,
            $evm
        );

        $schemaTool = new \Doctrine\ORM\Tools\SchemaTool($this->em);
        $schemaTool->createSchema(array_map(array($this->em, 'getClassMetadata'), $this->models));
    }


    private function setUpAudit(EventManager $evm) {
        $auditConfig = new AuditConfiguration();
        $auditConfig->setCurrentUsername("beberlei");
        $auditConfig->setAuditedEntityClasses($this->models);
        $auditConfig->setGlobalIgnoreColumns(array('ignoreme'));

        $this->auditManager = new AuditManager($auditConfig);
        $this->auditManager->registerEvents($evm);
    }


    public function setUp()
    {
        $evm = new EventManager();

        $this->setUpAudit($evm);
        $this->setUpEM($evm);

        $this->revisionListener = new RelationAudit();
        $evm->addEventSubscriber($this->revisionListener);
    }


    public function testRelationUpdated()
    {
        $m1a = new Model1("model1A");
        $m2a = new Model2("model2A");
        $mre = new RelationMN($m1a, $m2a);

        $this->em->persist($m1a);
        $this->em->persist($m2a);
        $this->em->persist($mre);
        $this->em->flush();

        $this->assertEquals(1, $this->em->getConnection()->fetchColumn('SELECT count(*) FROM revisions'));
        $this->assertEquals(1, $this->em->getConnection()->fetchColumn('SELECT count(*) FROM Model1_audit'));
        $this->assertEquals(1, $this->em->getConnection()->fetchColumn('SELECT count(*) FROM Model2_audit'));
        $this->assertEquals(1, $this->em->getConnection()->fetchColumn('SELECT count(*) FROM RelationMN_audit'));

        $mre->setTitle('title2');
        $this->em->flush();

        $this->assertEquals(2, $this->em->getConnection()->fetchColumn('SELECT count(*) FROM revisions'));
        $this->assertEquals(2, $this->em->getConnection()->fetchColumn('SELECT count(*) FROM RelationMN_audit'));

        // these two assertions are the core of test
        $this->assertEquals(1, $this->em->getConnection()->fetchColumn('SELECT count(*) FROM Model2_audit'), 'non marked relation without updated');
        $this->assertEquals(2, $this->em->getConnection()->fetchColumn('SELECT count(*) FROM Model1_audit'), 'marked relation with update');
    }


    public function testRelationCreated()
    {
        $m1a = new Model1("model1A");
        $m2a = new Model2("model2A");

        $this->em->persist($m1a);
        $this->em->persist($m2a);
        $this->em->flush();

        $this->assertEquals(1, $this->em->getConnection()->fetchColumn('SELECT count(*) FROM revisions'));
        $this->assertEquals(1, $this->em->getConnection()->fetchColumn('SELECT count(*) FROM Model1_audit'));
        $this->assertEquals(1, $this->em->getConnection()->fetchColumn('SELECT count(*) FROM Model2_audit'));

        $mre = new RelationMN($m1a, $m2a);
        $this->em->persist($mre);
        $this->em->flush();

        $this->assertEquals(2, $this->em->getConnection()->fetchColumn('SELECT count(*) FROM revisions'));
        $this->assertEquals(1, $this->em->getConnection()->fetchColumn('SELECT count(*) FROM RelationMN_audit'));

        // these two assertions are the core of test
        $this->assertEquals(1, $this->em->getConnection()->fetchColumn('SELECT count(*) FROM Model2_audit'), 'non marked relation without updated');
        $this->assertEquals(2, $this->em->getConnection()->fetchColumn('SELECT count(*) FROM Model1_audit'), 'marked relation with update');
    }

    public function testRelationRemoved()
    {
        $m1a = new Model1("model1A");
        $m2a = new Model2("model2A");
        $mre = new RelationMN($m1a, $m2a);

        $this->em->persist($m1a);
        $this->em->persist($m2a);
        $this->em->persist($mre);
        $this->em->flush();

        $this->assertEquals(1, $this->em->getConnection()->fetchColumn('SELECT count(*) FROM revisions'));
        $this->assertEquals(1, $this->em->getConnection()->fetchColumn('SELECT count(*) FROM Model1_audit'));
        $this->assertEquals(1, $this->em->getConnection()->fetchColumn('SELECT count(*) FROM Model2_audit'));
        $this->assertEquals(1, $this->em->getConnection()->fetchColumn('SELECT count(*) FROM RelationMN_audit'));

        $this->em->remove($mre);
        $this->em->flush();

        $this->assertEquals(2, $this->em->getConnection()->fetchColumn('SELECT count(*) FROM revisions'));
        $this->assertEquals(2, $this->em->getConnection()->fetchColumn('SELECT count(*) FROM RelationMN_audit'));

        // these two assertions are the core of test
        $this->assertEquals(1, $this->em->getConnection()->fetchColumn('SELECT count(*) FROM Model2_audit'), 'non marked relation without updated');
        $this->assertEquals(2, $this->em->getConnection()->fetchColumn('SELECT count(*) FROM Model1_audit'), 'marked relation with update');
    }

    // todo test for collection relation

    // todo - chaining of relations?
}


/**
 * @ORM\Entity
 */
class Model1 {

    /** @ORM\Id @ORM\Column(type="integer") @ORM\GeneratedValue */
    private $id;

    /** @ORM\Column(type="string") */
    private $title;

    /** @ORM\Column(type="datetimetz", nullable=true) */
    private $updatedAt;


    /**
     * @param string $title
     */
    public function __construct($title) {
        $this->setTitle($title);
    }

    /**
     * @return mixed
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @param mixed $updatedAt
     * @return $this
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $title
     * @return $this
     */
    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }
}


/**
 * @ORM\Entity
 */
class Model2 {

    /** @ORM\Id @ORM\Column(type="integer") @ORM\GeneratedValue */
    private $id;

    /** @ORM\Column(type="string") */
    private $title;

    /** @ORM\Column(type="datetimetz", nullable=true) */
    private $updatedAt;


    /**
     * @param string $title
     */
    public function __construct($title) {
        $this->setTitle($title);
    }


    /**
     * @return mixed
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @param mixed $updatedAt
     * @return $this
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $title
     * @return $this
     */
    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }
}


/**
 * @ORM\Entity
 */
class RelationMN {
    /** @ORM\Id @ORM\Column(type="integer") @ORM\GeneratedValue */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Model1")
     * @\Symbio\OrangeGate\RevisionBundle\Annotation\RelationAudit()
     */
    private  $model1;


    /** @ORM\ManyToOne(targetEntity="Model2") */
    private $model2;


    /** @ORM\Column(type="string") */
    private $title;


    /** @ORM\Column(type="datetimetz", nullable=true) */
    private $updatedAt;


    function __construct($model1, $model2)
    {
        $this->setModel1($model1);
        $this->setModel2($model2);
        $this->setTitle('default');
    }


    /**
     * @return mixed
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @param mixed $updatedAt
     * @return $this
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $title
     * @return $this
     */
    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getModel1()
    {
        return $this->model1;
    }

    /**
     * @RelationAudit()
     *
     * @param mixed $model1
     * @return $this
     */
    public function setModel1($model1)
    {
        $this->model1 = $model1;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getModel2()
    {
        return $this->model2;
    }

    /**
     * @param mixed $model2
     * @return $this
     */
    public function setModel2($model2)
    {
        $this->model2 = $model2;
        return $this;
    }
}