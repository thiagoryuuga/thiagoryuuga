<?php 

function tcpdf()
{
    //require_once('tcpdf/config/lang/eng.php');
    require_once('tcpdf/tcpdf.php');
}


/*if ( ! function_exists('exportMeAsDOMPDF'))
{
         function exportMeAsDOMPDF($htmView,$fileName) {

                    $CI =& get_instance();
                    $CI->load->helper(array('dompdf/dompdf', 'file'));
                    $CI->load->helper('file');
                    $pdfName = $fileName;
                    $pdfData = pdf_create("afastamento", $pdfName);
                    write_file('Progress Repost', $pdfData);   
        }
}*/




?>