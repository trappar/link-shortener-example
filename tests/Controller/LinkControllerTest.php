<?php

namespace App\Tests\Controller;

use App\Repository\LinkRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class LinkControllerTest extends WebTestCase
{
    private KernelBrowser $client;

    public function setUp()
    {
        $this->client = static::createClient();
    }

    public function testCreateDelete()
    {
        $this->createRequest();
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());

        $result = json_decode($this->client->getResponse()->getContent(), true);
        $code   = $result['code'];
        $secret = $result['secret'];

        $this->assertNotEmpty($this->findLink($code, $secret));

        $this->client->request('DELETE', '/api/link/' . $code . '?secret=' . $secret);
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertEmpty($this->findLink($code, $secret));
    }

    public function testCreateWithCode()
    {
        $desiredCode = (string) time();

        $this->createRequest($desiredCode);
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());

        $result = json_decode($this->client->getResponse()->getContent(), true);
        $code   = $result['code'];

        $this->assertEquals($code, $desiredCode);

        /** @var LinkRepository $repo */
        $repo = static::$container->get(LinkRepository::class);
        $repo->deleteByCode($desiredCode);
    }

    public function testCreateInvalidCode()
    {
        $this->createRequest('Invalid: &*(');
        $this->assertMatchesRegularExpression('~is not valid~', $this->client->getResponse()->getContent());
    }

    private function createRequest(?string $desiredCode = null)
    {
        $request = ['url' => 'http://test.com'];
        if ($desiredCode) {
            $request['desiredCode'] = $desiredCode;
        }

        return $this->client->request('POST', '/api/link', [], [], [], json_encode($request));
    }

    private function findLink($code, $secret)
    {
        $linkRepo = static::$container->get(LinkRepository::class);

        return $linkRepo->findOneBy([
            'code'   => $code,
            'secret' => $secret
        ]);
    }
}