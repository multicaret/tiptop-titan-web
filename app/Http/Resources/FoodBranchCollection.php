<?php


namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;


class FoodBranchCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return [
            'restaurants' => $this->collection,
            'pagination' => [
                'total' => $this->total(),
//                'count' => $this->count(),
                'perPage' => $this->perPage(),
                'currentPage' => $this->currentPage(),
                'totalPages' => $this->lastPage()
            ],
        ];
    }

    public function withResponse($request, $response)
    {
        $jsonResponse = json_decode($response->getContent(), true);
        unset($jsonResponse['links'], $jsonResponse['meta']);

        $response->setContent(json_encode($jsonResponse));
    }

}
