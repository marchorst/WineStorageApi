<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Wine;
use Symfony\Component\HttpKernel\Attribute\AsController;

#[AsController]
class WineImageController extends AbstractController
{
    #[Route('/api/wines/image/{id}', name: 'app_wine_image_upload', methods: ['PUT', 'POST', 'PATCH'])]
    public function uploadImage(
        Wine $wine,
        Request $request,
        EntityManagerInterface $entityManager,
        SluggerInterface $slugger
    ): Response {
        $wineImage = $request->files->get("file");
        // this condition is needed because the 'brochure' field is not required
        // so the PDF file must be processed only when a file is uploaded
      
              // Create a Symfony File object from the temporary file
            $wineImage = new File($wineImage);

            $originalFilename = pathinfo($wineImage, PATHINFO_BASENAME);

            // this is needed to safely include the file name as part of the URL
            $safeFilename = $slugger->slug($originalFilename);

            $newFilename =
                $safeFilename .
                '-' .
                uniqid() .
                '.' .
                $wineImage->guessExtension();
            $wineImage->move(
                $this->getParameter('wine_directory'),
                $newFilename
            );

        if($wine->getWineImage()) {
            
            unlink($this->getParameter('kernel.project_dir')."/public".$wine->getWineImage());
        }
        $wine->setWineImage($newFilename);

        $entityManager->persist($wine);
        $entityManager->flush();
        $retVal[] = $newFilename;        
        return new Response(json_encode($retVal),Response::HTTP_OK, ['content-type' => 'text/json' ]);
    }

    #[Route('/api/wines/image/{id}', name: 'app_wine_image_remove', methods: ['DELETE'])]
    public function deleteImage(
        Wine $wine,
        Request $request,
        EntityManagerInterface $entityManager,
        SluggerInterface $slugger
    ): Response {
       
        if($wine->getWineImage()) {
            
            unlink($this->getParameter('kernel.project_dir')."/public".$wine->getWineImage());
        }
        $wine->setWineImage("");

        $entityManager->persist($wine);
        $entityManager->flush();
        $retVal[] = "";        
        return new Response(json_encode($retVal),Response::HTTP_OK, ['content-type' => 'text/json' ]);
    }
}
