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

class VivinoWineImporterController extends AbstractController
{
    #[Route('/wine/importer/tryvivinoimageimport', name: 'app_wine_importer_vivinoimages', methods: ['GET', 'POST'])]
    public function tryvivinoimageimport(
        Request $request,
        EntityManagerInterface $entityManager,
        SluggerInterface $slugger
    ): Response {
        $wineRepo = $entityManager->getRepository(Wine::class);

        $wines = $wineRepo->findBy([
            'WineImage' => null
        ], null, 1);
        set_time_limit(120);
        $retVal = [];
        foreach ($wines as $wine) {
            $searchterm =
                $wine->getName() .
                '+' .
                $wine->getVintage() .
                '+' .
                $wine->getProducer();
            $searchterm = urlencode($searchterm);
            $url = 'https://www.vivino.com/search/wines?q=' . $searchterm;
            $userAgent =
                'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.110 Safari/537.3';
            // Get HTML content from the URL
            $html = $this->getUrlContent($url, $userAgent);

            $value = $this->getBackgroundImageUrl(
                $html,
                "//*[@class='wine-card__image']"
            );
            if (!empty($value)) {
                $value = str_replace('300x300', '600x600', $value);

                // Fetch the content of the file
                $content = $this->getUrlContent('https:' . $value, $userAgent);

                // Create a temporary file and write the content into it
                $temporaryFilePath = tempnam(
                    sys_get_temp_dir(),
                    'imported_file'
                );
                file_put_contents($temporaryFilePath, $content);

                // Create a Symfony File object from the temporary file
                $wineImage = new File($temporaryFilePath);

                $originalFilename = pathinfo($temporaryFilePath, PATHINFO_BASENAME);

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
            } else {
                $newFilename = "default.png";
            }
            $wine->setWineImage($newFilename);

            $entityManager->persist($wine);
            $entityManager->flush();
            $retVal[] = $newFilename;
        }
        
        return new Response(json_encode($retVal),Response::HTTP_OK, ['content-type' => 'text/json' ]);
    }

    private function getUrlContent($url, $userAgent)
    {
        sleep(rand(1000, 4000)/1000);
        // Create a stream context with the desired user agent
        $options = [
            'http' => [
                'header' => "User-Agent: $userAgent",
            ],
        ];

        $context = stream_context_create($options);

        // Get the HTML content from the URL
        $html = file_get_contents($url, false, $context);

        return $html;
    }

    private function getBackgroundImageUrl($html, $selector)
    {
        // Create a new DOMDocument
        $dom = new \DOMDocument();

        // Load the HTML content into the DOMDocument
        libxml_use_internal_errors(true);
        $dom->loadHTML($html);
        libxml_use_internal_errors(false);

        // Create a DOMXPath object to query the document
        $xpath = new \DOMXPath($dom);

        // Use the provided selector to query the document and get the style attribute
        $result = $xpath->query($selector);

        // Extract the background image URL from the style attribute
        if ($result->length > 0) {
            $style = $result[0]->getAttribute('style');

            // Use a regular expression to extract the URL from the style attribute
            preg_match(
                '/background-image:\s*url\([\'"]?([^\'"]+)[\'"]?\)/',
                $style,
                $matches
            );

            // Return the extracted URL (or null if not found)
            return isset($matches[1]) ? $matches[1] : null;
        }

        return null;
    }
}