<?php

namespace Janmuran;

use Exception;
use Janmuran\Config\Constant;
use Janmuran\Response\Response;

class TrustedShop
{
    protected $webApiKey;
    protected $email = null;
    protected $products = [];

    public function __construct(string $webApiKey) {
        $this->webApiKey = $webApiKey;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @param string $productName - A product name from the customer's cart.
     * @param ?string $productId - A product id, it must be same as in the feed.
     */
    public function addProduct(string $productName, ?string $productId = null)
    {
        $content = [];
        $content['Name'] = $productName;
        if ($productId !== null) {
            $content['Id'] = $productId;
        }
        $this->products[] = $content;
    }

    /** 
     * Create the Trusted code, which provides data sending from the customer's browser to arukereso.
     * @return string - Prepared Trusted code (HTML).
     */
    public function createTrustedCode(): string
    {
        if (empty($this->webApiKey)) {
            throw new Exception(Constant::ERROR_EMPTY_WEBAPIKEY);
        }

        if (empty($this->email)) {
            throw new Exception(Constant::ERROR_EMPTY_EMAIL);
        }

        $params = [];
        $params['Version'] = Constant::VERSION;
        $params['WebApiKey'] = $this->webApiKey;
        $params['Email'] = $this->email;
        $params['Products'] = json_encode($this->products);

        $query = $this->getQuery($params);

        return Response::create($this->webApiKey, $query);
    }

    /**
     * Performs a request on Arukereso servers to get a token and assembles query params with it.
     * @param array $params - Parameters to send with token request.
     * @return string - Query string to assemble sending code snipet on client's side with it.
     */
    protected function getQuery($params)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, Constant::SERVICE_URL_SEND . Constant::SERVICE_TOKEN_REQUEST);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($params));
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT_MS, 500);
        curl_setopt($curl, CURLOPT_TIMEOUT_MS, 500);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_HEADER, true);

        $response = curl_exec($curl);

        if (curl_errno($curl) === 0 && $response !== false) {
            $info = curl_getinfo($curl);
            $statusCode = $info['http_code'];

            $jsonBody = substr($response, $info['header_size']);
            $jsonArray = json_decode($jsonBody, true);
            $jsonError = json_last_error();

            curl_close($curl);

            if (empty($jsonError)) {
                if ($statusCode == 200) {
                    $query = [];
                    $query[] = 'Token=' . $jsonArray['Token'];
                    $query[] = 'webApiKey=' . $this->webApiKey;
                    $query[] = 'C=';
                    return '?' . join('&', $query);
                } else if ($statusCode == 400) {
                    throw new Exception(Constant::ERROR_TOKEN_BAD_REQUEST . $jsonArray['ErrorCode'] . ' - ' . $jsonArray['ErrorMessage']);
                } else {
                    throw new Exception(Constant::ERROR_TOKEN_REQUEST_FAILED);
                }
            } else {
                throw new Exception('Json error: ' . $jsonError);
            }
        } else {
            throw new Exception(Constant::ERROR_TOKEN_REQUEST_TIMED_OUT);
        }
    }
}