<?php

namespace App\Services;

use Greenter\Model\Client\Client;
use Greenter\Model\Company\Company;
use Greenter\Model\Company\Address;
use Greenter\Model\Sale\Invoice;
use Greenter\Model\Sale\SaleDetail;
use Greenter\Model\Sale\Legend;
use Greenter\See;
use Greenter\Ws\Services\SunatEndpoints;
use Greenter\XMLSecLibs\Certificate\X509Certificate;
use Greenter\Report\XmlUtils;

class GreenterService
{
    protected $see;
    protected $company;

    public function __construct()
    {
        $this->see = new See();
        $this->see->setCertificate(file_get_contents(storage_path('app/certificates/certificate.pem')));
        $this->see->setService(SunatEndpoints::FE_BETA);

        $this->company = new Company();
        $this->company->setRuc('20000000001')
            ->setRazonSocial('EMPRESA S.A.C.')
            ->setNombreComercial('EMPRESA')
            ->setAddress((new Address())
                ->setUbigueo('150101')
                ->setDepartamento('LIMA')
                ->setProvincia('LIMA')
                ->setDistrito('LIMA')
                ->setUrbanizacion('NONE')
                ->setDireccion('AV LS 123'));
    }

    public function generateInvoice($sale)
    {
        $client = new Client();
        $client->setTipoDoc('6')
            ->setNumDoc($sale->cliente_ruc)
            ->setRznSocial($sale->cliente_nombre);

        $invoice = (new Invoice())
            ->setUblVersion('2.1')
            ->setTipoOperacion('0101')
            ->setTipoDoc('01')
            ->setSerie('F001')
            ->setCorrelativo((string)$sale->id)
            ->setFechaEmision(new \DateTime())
            ->setTipoMoneda('PEN')
            ->setClient($client)
            ->setCompany($this->company)
            ->setMtoOperGravadas($sale->total_amount / 1.18)
            ->setMtoIGV($sale->total_amount - ($sale->total_amount / 1.18))
            ->setTotalImpuestos($sale->total_amount - ($sale->total_amount / 1.18))
            ->setValorVenta($sale->total_amount / 1.18)
            ->setMtoImpVenta($sale->total_amount);

        $items = [];
        foreach ($sale->items as $item) {
            $items[] = (new SaleDetail())
                ->setCodProducto($item->product->code)
                ->setUnidad('NIU')
                ->setCantidad($item->quantity)
                ->setMtoValorUnitario($item->price / 1.18)
                ->setDescripcion($item->product->name)
                ->setMtoBaseIgv($item->price * $item->quantity / 1.18)
                ->setPorcentajeIgv(18)
                ->setIgv(($item->price * $item->quantity) - ($item->price * $item->quantity / 1.18))
                ->setTipAfeIgv('10')
                ->setTotalImpuestos(($item->price * $item->quantity) - ($item->price * $item->quantity / 1.18))
                ->setMtoValorVenta($item->price * $item->quantity / 1.18)
                ->setMtoPrecioUnitario($item->price);
        }

        $invoice->setDetails($items);

        $legend = (new Legend())
            ->setCode('1000')
            ->setValue('SON ' . $this->convertNumberToWords($sale->total_amount) . ' SOLES');

        $invoice->setLegends([$legend]);

        $result = $this->see->send($invoice);

        // Si la factura se generó correctamente
        if ($result->isSuccess()) {
            // Obtener el XML firmado
            $xml = $this->see->getFactory()->getLastXml();
            
            // Obtener el CDR (Constancia de Recepción)
            $cdr = $result->getCdrResponse();
            
            // Obtener el código hash del documento
            $hash = (new XmlUtils())->getHashSign($xml);

            return (object)[
                'success' => true,
                'xml' => $xml,
                'cdr' => $cdr->getDescription(),
                'hash' => $hash,
                'serie' => $invoice->getSerie(),
                'correlativo' => $invoice->getCorrelativo()
            ];
        }

        return (object)[
            'success' => false,
            'error' => $result->getError()->getMessage()
        ];
    }

    private function convertNumberToWords($number)
    {
        // Implementa la lógica para convertir números a palabras
        return "CIEN"; // Ejemplo simplificado
    }
}

