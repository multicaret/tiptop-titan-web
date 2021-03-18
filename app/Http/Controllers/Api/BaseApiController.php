<?php

namespace App\Http\Controllers\Api;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller;
use Symfony\Component\HttpFoundation\Response;

class BaseApiController extends Controller
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    const PAGINATION = 30;
    const NOTIFICATION_LED_COLOR = '34495e';

    private $statusCode = Response::HTTP_OK;

    /**
     * @return int
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * @param  int  $statusCode
     *
     * @return $this
     */
    public function setStatusCode($statusCode)
    {
        $this->statusCode = $statusCode;

        return $this;
    }

    public function respondNotFound($message = 'Not Found!')
    {
        return $this->setStatusCode(Response::HTTP_NOT_FOUND)
                    ->respondWithMessage($message);
    }

    public function respondWithMessage($message)
    {
        return $this->respond(null, null, $message);
    }

    public function respondValidationFails($errors, $data = null)
    {
        return $this->setStatusCode(Response::HTTP_UNPROCESSABLE_ENTITY)
                    ->respond($data, $errors, 'Invalid Data');
    }

    public function respond($data, $errors = '', $message = '', $headers = [])
    {

        return response()->json([
            'data' => $data,
            'errors' => $errors,
            'message' => $message,
            'status' => $this->getStatusCode(),
        ], $this->getStatusCode(), $headers);
    }
}
