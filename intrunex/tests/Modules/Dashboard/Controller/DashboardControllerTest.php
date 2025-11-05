<?php

namespace App\Tests\Modules\Dashboard\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DashboardControllerTest extends WebTestCase
{
    public function testDashboard()
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(\App\Repository\UserRepository::class);
        $testUser = $userRepository->findOneByEmail('test@test.com');
        $client->loginUser($testUser);
        $crawler = $client->request('GET', '/dashboard');
        $this->assertResponseIsSuccessful();
        $this->assertGreaterThanOrEqual(0, $crawler->filter('#vulnerableAssetsCount')->text());
    }
}