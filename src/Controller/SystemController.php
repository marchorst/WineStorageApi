<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SystemController extends AbstractController
{
    #[Route('/system/update', name: 'app_system')]
    public function index(): Response
    {
        $path = realpath(dirname(__FILE__)); 
        $r = exec("cd ".$path." && cd ../../ && git pull");
        return new Response(json_encode($r),Response::HTTP_OK, ['content-type' => 'text/json' ]);
  
    }
}
