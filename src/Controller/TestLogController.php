<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Psr\Log\LoggerInterface;

class TestLogController extends AbstractController
{
    private LoggerInterface $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function logTest(): Response
    {
        $this->logger->info('Test message for newbook logger');
        return new Response('Log entry created');
    }

}
