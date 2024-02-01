<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaValidator;
use Doctrine\ORM\Tools\SchemaTool;
use Symfony\Component\HttpFoundation\JsonResponse;

class SystemController extends AbstractController
{
    #[Route('/update', name: 'systemupdate_index')]
    public function index(EntityManagerInterface $entityManager): JsonResponse
    {
        $outputs=shell_exec('/bin/bash update.sh');
        return $this->json($outputs);
    }
    #[Route('/version', name: 'version')]
    public function version(EntityManagerInterface $entityManager): JsonResponse
    {
        return $this->json("1.0");
    }
}
