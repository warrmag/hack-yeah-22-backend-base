<?php

namespace App\Service;

use App\DTO\BarCodeLookupProduct;
use App\Exception\BarCodeLookupProductNotFoundException;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class BarCodeLookupClient
{
    public function __construct(
        private readonly HttpClientInterface $client,
        private readonly string $bclApiUrl,
        private readonly string $bclApiKey,
    ) {}

    public function findProductByBarCode(string $barCode): BarCodeLookupProduct
    {
        $response = $this->client->request('GET', $this->bclApiUrl, [
            'query' => [
                'barcode' => $barCode,
                'key' => $this->bclApiKey
            ],
        ]);

        if ($response->getStatusCode() !== 200) {
            throw new BarCodeLookupProductNotFoundException($barCode);
        }

        $productData = $response->toArray()['products'][0];

        return new BarCodeLookupProduct(
            title: $productData['title'],
            description: $productData['description'],
            barCode: $productData['barcode_number'],
            imageUrl: $productData['images'][0]
        );
    }
}
