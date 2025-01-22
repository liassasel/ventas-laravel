<?php

return [
    'cert' => [
        'path' => env('SUNAT_CERT_PATH', storage_path('app/certificates/certificate.pem')),
        'password' => env('SUNAT_CERT_PASS', '')
    ],
    'company' => [
        'ruc' => env('SUNAT_RUC', '20000000001'),
        'razon_social' => env('SUNAT_RAZON_SOCIAL', 'EMPRESA S.A.C.'),
        'nombre_comercial' => env('SUNAT_NOMBRE_COMERCIAL', 'EMPRESA'),
        'address' => [
            'ubigeo' => '150101',
            'departamento' => 'LIMA',
            'provincia' => 'LIMA',
            'distrito' => 'LIMA',
            'urbanizacion' => 'NONE',
            'direccion' => 'AV LS 123'
        ]
    ],
    'sunat' => [
        'endpoint' => 'https://e-beta.sunat.gob.pe/ol-ti-itcpfegem-beta/billService',
        'user' => env('SUNAT_SOL_USER', 'MODDATOS'),
        'pass' => env('SUNAT_SOL_PASS', 'MODDATOS')
    ]
];