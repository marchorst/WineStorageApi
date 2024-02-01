<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaValidator;
use Doctrine\ORM\Tools\SchemaTool;
use Symfony\Component\HttpFoundation\JsonResponse;

class DatabaseController extends AbstractController
{
    #[Route('/database', name: 'database_index')]
    public function index(EntityManagerInterface $entityManager): JsonResponse
    {
        // Create a SchemaValidator instance
        $validator = new SchemaValidator($entityManager);
        $schemaTool = new SchemaTool($entityManager);
        $conn            = $entityManager->getConnection();
    
        $mapping = $validator->getUpdateSchemaList();
        $result = true;
        foreach($mapping as $sql) {
           
            if($conn->executeStatement($sql) != 0)
                $result = false;
            
        }
        return $this->json(["sqls"=>$mapping, "result"=>$result]);
    }
}
