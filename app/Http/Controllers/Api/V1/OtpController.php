<?php

namespace App\Http\Controllers\Api\V1;


use App\Http\Controllers\Api\BaseApiController;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use VerifyKit\Exception\CountryCodeEmptyException;
use VerifyKit\Exception\CurlException;
use VerifyKit\Exception\OTPCodeEmptyException;
use VerifyKit\Exception\PhoneNumberEmptyException;
use VerifyKit\Exception\ReferenceEmptyException;
use VerifyKit\Exception\ServerKeyEmptyException;
use VerifyKit\VerifyKit;
use VerifyKit\Web;

class OtpController extends BaseApiController
{
    public function methods(): JsonResponse
    {
        $errors = '';
        $response = [];
        try {
            $vfk = $this->getVFK();
            $validationMethodList = $vfk->getValidationMethodList();
            $response = collect($validationMethodList->getList())->map(function ($method) {
                return [
                    'methodKey' => $method->getApp(),
                    'methodName' => $method->getName(),
                    'text' => [
                        'content' => $method->getText(),
                        'textColor' => $method->getTextColour(),
                        'backgroundColor' => $method->getBgColour(),
                    ],
                    'icon' => $method->getIconPath(),
                ];
            })->toArray();
        } catch (CurlException $e) {
            $errors = $e->getMessage();
        }

        return $this->respond($response, $errors);
    }

    public function init(Request $request)
    {
        $validationMethod = $request->input('method', 'whatsapp'); // whatsapp or telegram. Required.

        $lang = localization()->getCurrentLocale();
        $deeplink = ! $request->exists('qrCode');
        $vfk = $this->getVFK();
        $validationStart = $vfk->startValidation($validationMethod, $lang, $deeplink, ! $deeplink);
        $response = [
            $deeplink ? 'deeplink' : 'qrCode' => $validationStart->getDeeplink(),
            'reference' => $validationStart->getReference(),
        ];

        return $this->respond($response);
    }

    public function check($reference, Request $request): JsonResponse
    {
        $validationRules = [
            'phone_number' => 'required|numeric|digits_between:7,15',
            'phone_country_code' => 'required|min:1|max:3',
            'mobile_app_details' => 'json',
        ];

        $validator = validator()->make($request->all(), $validationRules);
        if ($validator->fails()) {
            return $this->respondValidationFails($validator->errors());
        }


        try {
            $vfk = $this->getVFK();
            $validationCheck = $vfk->checkValidation($reference);
            if ( ! $validationCheck->getValidationStatus()) {
                $errorMessage = $validationCheck->getErrorMessage();
                $statusCode = $validationCheck->getHttpStatusCode();
            }
        } catch (CurlException | ReferenceEmptyException $e) {
            $errorMessage = $e->getMessage();
            $statusCode = Response::HTTP_UNAUTHORIZED;
        }

        if (isset($errorMessage) && isset($statusCode)) {
            return $this->setStatusCode($statusCode)->respondWithMessage($errorMessage);
        }
        $validationStatus = isset($validationCheck) ? $validationCheck->getValidationStatus() : false;

        $newUser = false;
        if ($validationStatus) {
            [$user, $newUser, $accessToken] = $this->registerUserIfNotFoundByPhone(
                $request->input('phone_country_code'),
                $request->input('phone_number'),
                $request->input('mobile_app_details')
            );
            $response = [
                'newUser' => $newUser,
                'user' => new UserResource($user),
                'accessToken' => $accessToken,
                'validationStatus' => $validationStatus,
                'sessionId' => isset($validationCheck) ? $validationCheck->getSessionId() : null,
                // session id for the validation result
                'appPlatform' => isset($validationCheck) ? $validationCheck->getAppPlatform() : null,
                // web, android or ios
            ];
        } else {
            $response = [
                'newUser' => $newUser,
                'user' => null,
                'accessToken' => null,
                'validationStatus' => $validationStatus,
                // session id for the validation result
                'sessionId' => isset($validationCheck) ? $validationCheck->getSessionId() : null,
                // web, android or ios
                'appPlatform' => isset($validationCheck) ? $validationCheck->getAppPlatform() : null,
            ];
        }

        return $this->respond($response);

    }

    public function otpSmsSend(Request $request): JsonResponse
    {
        $validationRules = [
            'country_code' => 'required|min:2|max:2',
            'phone_country_code' => 'required|min:1|max:3',
            'phone_number' => 'required|numeric|digits_between:7,15',
            'mobile_app_details' => 'json',
        ];

        $validator = validator()->make($request->all(), $validationRules);
        if ($validator->fails()) {
            return $this->respondValidationFails($validator->errors());
        }

        // For OTP verification to work best, you should send us the MCC and MNC code of the sim card in the user's device.
        $mcc = '999'; // Mobile Country Code (MCC) of the sim card in the user's device. Default value is '999'. Not required.
        $mnc = '999'; // Mobile Network Code (MNC) of the sim card in the user's device. Default value is '999'. Not required.

        $phoneNumber = $request->input('phone_number');  // '+90'.$request->phone;
        $countryCode = $request->input('country_code');  // 'TR';

        $lang = localization()->getCurrentLocale();

        try {
            $vfk = $this->getVFK();
            $result = $vfk->sendOTP($phoneNumber, $countryCode, $mcc, $mnc, $lang);
        } catch (CountryCodeEmptyException | PhoneNumberEmptyException | CurlException $e) {
            $errorMessage = $e->getMessage();
        }


        if (isset($errorMessage)) {
            return $this->setStatusCode(Response::HTTP_BAD_REQUEST)->respondWithMessage($errorMessage);
        }

        $reference = isset($result) ? $result->getReference() : null; // This parameter is required for a check OTP request.

        $response = [
            'phoneNumber' => $phoneNumber,
            'countryCode' => $countryCode,
            'reference' => $reference,
        ];

        return $this->respond($response);
    }

    public function otpSmsValidate(Request $request): JsonResponse
    {
        $validationRules = [
            'country_code' => 'required|min:2|max:2',
            'phone_country_code' => 'required|min:1|max:3',
            'phone_number' => 'required|numeric|digits_between:7,15',
            'code' => 'required|numeric|min:4|max:8',
            'reference' => 'required',
            'mobile_app_details' => 'json',
        ];

        $validator = validator()->make($request->all(), $validationRules);
        if ($validator->fails()) {
            return $this->respondValidationFails($validator->errors());
        }


        $statusCode = Response::HTTP_BAD_REQUEST;
        $phoneNumber = $request->input('phone_number');
        $phoneCountryCode = $request->input('phone_country_code');
        $countryCode = $request->input('country_code');
        $code = $request->input('code');
        $reference = $request->input('reference');

        try {
            $vfkWeb = $this->getVFK();
            $otpCheck = $vfkWeb->checkOtp($phoneCountryCode.$phoneNumber, $countryCode, $reference, $code);
            if ($otpCheck->getValidationStatus()) {
                [$serverKey, $clientIp] = $this->getServerKeyAndClientIP();
                $VFK = new VerifyKit($serverKey, $clientIp);
                $result = $VFK->getResult($otpCheck->getSessionId());
                if ($result->isSuccess()) {
                    /*$response = [
                        'phoneNumber' => $result->getPhoneNumber(),
                        'validationType' => $result->getValidationType(),
                        'validationDate' => $result->getValidationDate()->format('Y-m-d H:i:s'),
                    ];*/

                    [$user, $newUser, $accessToken] = $this->registerUserIfNotFoundByPhone(
                        $phoneCountryCode,
                        $phoneNumber,
                        $request->input('mobile_app_details')
                    );
                    $response = [
                        'newUser' => $newUser,
                        'user' => new UserResource($user),
                        'accessToken' => $accessToken,
                        'validationStatus' => $result->isSuccess(),
                        // session id for the validation result
                        'sessionId' => isset($validationCheck) ? $validationCheck->getSessionId() : null,
                        // web, android or ios
                        'appPlatform' => isset($validationCheck) ? $validationCheck->getAppPlatform() : null,
                    ];

                    return $this->respond($response);
                } else {
                    $errorMessage = $result->getErrorMessage().", error code : ".$result->getErrorCode();
                }
            } else {
                $errorMessage = $otpCheck->getErrorMessage();
                $statusCode = $otpCheck->getHttpStatusCode();
            }
        } catch (CountryCodeEmptyException | PhoneNumberEmptyException |
        OTPCodeEmptyException | CurlException |
        ReferenceEmptyException | ServerKeyEmptyException | \Exception $e) {
            $errorMessage = $e->getMessage();
        }

        if (isset($errorMessage)) {
            return $this->setStatusCode($statusCode)->respondWithMessage($errorMessage);
        }

        return $this->respondNotFound();
    }


    /**
     * @return \Exception|ServerKeyEmptyException|Web
     */
    private function getVFK()
    {
        try {
            [$serverKey, $clientIp] = $this->getServerKeyAndClientIP();

            return new Web($serverKey, $clientIp);
        } catch (ServerKeyEmptyException $e) {
            return $e;
        }
    }

    private function getServerKeyAndClientIP(): array
    {
        $serverKey = env('VERIFYKIT_SERVER_KEY');
        $clientIp = request()->ip();

        return [$serverKey, $clientIp];
    }

    /**
     * @param $phoneCountryCode
     * @param $phoneNumber
     * @param $mobileDataRequest
     * @return array
     */
    private function registerUserIfNotFoundByPhone($phoneCountryCode, $phoneNumber, $mobileDataRequest): array
    {
        $mobileAppData = json_decode($mobileDataRequest);
        $deviceName = isset($mobileAppData->device) ? $mobileAppData->device->name : 'New Device';

        $newUser = false;
        // new user has been verified
        if (is_null($user = User::getUserByPhone($phoneCountryCode, $phoneNumber))) {
            $user = new User();
            $user->first = $phoneNumber;
            $user->phone_country_code = $phoneCountryCode;
            $user->phone_number = $phoneNumber;
//            $user->email = $phoneNumber.'@otp';
            $user->username = $phoneNumber;
            $user->approved_at = now();
            $user->phone_verified_at = now();
            $newUser = true;
        }
        $user->last_logged_in_at = now();
        $user->save();

        $accessToken = $user->createToken($deviceName, $mobileAppData)->plainTextToken;
        event(new Registered($user));

        return [$user, $newUser, $accessToken];
    }
}
