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
use Illuminate\Support\Facades\Log;
use Exception;

class GreenterService
{
    protected $see;
    protected $company;

    public function __construct()
    {
        $this->initializeSee();
        $this->initializeCompany();
    }

    protected function initializeSee()
{
    try {
        $this->see = new See();
        
        $certificatePath = storage_path('app/certificates/LLAMAPECERTIFICADODEMO20452578951.pem');
        
        if (!file_exists($certificatePath)) {
            throw new Exception("El certificado no existe en: $certificatePath");
        }

        $pfx = file_get_contents($certificatePath);
        if ($pfx === false) {
            throw new Exception("No se pudo leer el certificado");
        }

        // Asegúrate de que el certificado esté en el formato correcto
        $this->see->setCertificate($pfx);
        
        // Configura el servicio para ambiente Beta
        $this->see->setService(SunatEndpoints::FE_BETA);

        Log::info('Certificado configurado correctamente');
    } catch (Exception $e) {
        Log::error('Error al configurar el certificado: ' . $e->getMessage());
        throw new Exception('Error al configurar el certificado digital: ' . $e->getMessage());
    }
}

    protected function initializeCompany()
    {
        $this->company = new Company();
        $this->company->setRuc(config('greenter.company.ruc', '20000000001'))
            ->setRazonSocial(config('greenter.company.razon_social', 'EMPRESA S.A.C.'))
            ->setNombreComercial(config('greenter.company.nombre_comercial', 'EMPRESA'))
            ->setAddress((new Address())
                ->setUbigueo(config('greenter.company.address.ubigeo', '150101'))
                ->setDepartamento(config('greenter.company.address.departamento', 'LIMA'))
                ->setProvincia(config('greenter.company.address.provincia', 'LIMA'))
                ->setDistrito(config('greenter.company.address.distrito', 'LIMA'))
                ->setUrbanizacion(config('greenter.company.address.urbanizacion', 'NONE'))
                ->setDireccion(config('greenter.company.address.direccion', 'AV LS 123')));
    }

    public function generateInvoice($sale)
    {
        try {
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
            
            if (!$result->isSuccess()) {
                throw new Exception($result->getError()->getMessage());
            }

            return (object)[
                'success' => true,
                'serie' => $invoice->getSerie(),
                'correlativo' => $invoice->getCorrelativo(),
                'xml' => $this->see->getFactory()->getLastXml(),
                'hash' => $result->getHash(),
                'cdr' => $result->getCdrResponse()->getDescription()
            ];

        } catch (Exception $e) {
            Log::error('Error generando factura electrónica: ' . $e->getMessage());
            return (object)[
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    private function convertNumberToWords($number)
    {
        // Implementación simple para ejemplo
        return "CIEN";
    }
}

