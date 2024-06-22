<?php

namespace App\Test\Controller;

use App\Entity\Event;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class EventControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private EntityManagerInterface $manager;
    private EntityRepository $repository;
    private string $path = '/event/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->manager = static::getContainer()->get('doctrine')->getManager();
        $this->repository = $this->manager->getRepository(Event::class);

        foreach ($this->repository->findAll() as $object) {
            $this->manager->remove($object);
        }

        $this->manager->flush();
    }

    public function testIndex(): void
    {
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Event index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

    public function testNew(): void
    {
        $this->markTestIncomplete();
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'event[title]' => 'Testing',
            'event[description]' => 'Testing',
            'event[date]' => 'Testing',
            'event[maxpeople]' => 'Testing',
            'event[public]' => 'Testing',
        ]);

        self::assertResponseRedirects($this->path);

        self::assertSame(1, $this->repository->count([]));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Event();
        $fixture->setTitle('My Title');
        $fixture->setDescription('My Title');
        $fixture->setDate('My Title');
        $fixture->setMaxpeople('My Title');
        $fixture->setPublic('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Event');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Event();
        $fixture->setTitle('Value');
        $fixture->setDescription('Value');
        $fixture->setDate('Value');
        $fixture->setMaxpeople('Value');
        $fixture->setPublic('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'event[title]' => 'Something New',
            'event[description]' => 'Something New',
            'event[date]' => 'Something New',
            'event[maxpeople]' => 'Something New',
            'event[public]' => 'Something New',
        ]);

        self::assertResponseRedirects('/event/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getTitle());
        self::assertSame('Something New', $fixture[0]->getDescription());
        self::assertSame('Something New', $fixture[0]->getDate());
        self::assertSame('Something New', $fixture[0]->getMaxpeople());
        self::assertSame('Something New', $fixture[0]->getPublic());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();
        $fixture = new Event();
        $fixture->setTitle('Value');
        $fixture->setDescription('Value');
        $fixture->setDate('Value');
        $fixture->setMaxpeople('Value');
        $fixture->setPublic('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertResponseRedirects('/event/');
        self::assertSame(0, $this->repository->count([]));
    }
}
