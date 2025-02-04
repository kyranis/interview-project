<?php
declare(strict_types=1);

namespace App\Tests\Controller;

use App\Message\SendMessage;
use App\Tests\ServiceTestCase;
use Symfony\Component\HttpFoundation\Response;
use Zenstruck\Messenger\Test\InteractsWithMessenger;

class MessageControllerTest extends ServiceTestCase
{
    use InteractsWithMessenger;

    function test_list_without_status_filter(): void
    {
        $client = static::createClient();

        $this->loadTestData();

        $client->request('GET', '/messages');
        $response = $client->getResponse();

        $this->assertResponseIsSuccessful();

        $this->assertIsNotBool($response->getContent());
        $content = json_decode($response->getContent(), true);
        $this->assertIsArray($content);
        $this->assertCount(2, $content['messages']);
    }

    function test_list_with_status_filter(): void
    {
        $client = static::createClient();

        $this->loadTestData();

        $client->request('GET', '/messages', ['status'=>'sent']);
        $response = $client->getResponse();
        $this->assertResponseIsSuccessful();

        $this->assertIsNotBool($response->getContent());
        $content = json_decode($response->getContent(), true);
        $this->assertIsArray($content);
        $this->assertCount(1, $content['messages']);
        $this->assertEquals('sent', $content['messages'][0]['status']);
    }

    function test_sends_a_message(): void
    {
        $client = static::createClient();
        $client->request('POST', '/messages/send', [
            'text' => 'Hello World',
        ]);

        $this->assertResponseIsSuccessful();
        // This is using https://packagist.org/packages/zenstruck/messenger-test
        $this->transport('sync')
            ->queue()
            ->assertContains(SendMessage::class, 1);
    }

    function test_sends_a_message_without_text(): void
    {
        $client = static::createClient();
        $client->request('POST', '/messages/send', [
            'text' => '',
        ]);

        $this->assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST, 'Text is required');
        // This is using https://packagist.org/packages/zenstruck/messenger-test
        $this->transport('sync')
            ->queue()
            ->assertContains(SendMessage::class, 0);
    }
}