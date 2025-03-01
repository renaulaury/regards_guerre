<?php

namespace App\Service;

use App\Entity\Order; 
use Dompdf\Dompdf; //bibliotheque php pour transformer doc html en pdf
use Dompdf\Options; //Permet de personnaliser le pdf
use Twig\Environment; //Remplace EngineInterface pour rendre la vue


class PdfService
{
    private Environment $twig;

    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }

   
    public function generatePdf(Order $order): string
    {
        // Configuration des options de Dompdf
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');

        $dompdf = new Dompdf($pdfOptions); // Création d'une instance de Dompdf avec les options

        // Génération du HTML à partir du template Twig
        $html = $this->twig->render('pdf/order_pdf.html.twig', [
            'order' => $order, 
        ]);

        //Mep options dompdf
        $dompdf->loadHtml($html); // Chargement du HTML dans Dompdf
        $dompdf->setPaper('A4', 'portrait'); // Config du format de papier et de l'orientation
        $dompdf->render(); // Génération du PDF

        return $dompdf->output(); // Retourne le contenu du PDF
    }
}