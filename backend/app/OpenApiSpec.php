<?php

namespace App;

use OpenApi\Attributes as OA;

#[OA\Info(
    version: '1.0.0',
    title: 'Mini-CMS API',
    description: 'API documentation for the Mini-CMS project'
)]
#[OA\Server(
    url: 'http://127.0.0.1:8000',
    description: 'Local server'
)]
class OpenApiSpec
{
}