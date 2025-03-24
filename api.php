<?php
class Configuration
{
    private $host    = 'https://api.arubabusiness.it';
    private $apiKey  = "0000000000000000000";
    private $userName = '0000000000000000000';
    private $password = '0000000000000000000';
    private $token   = '';
    // private $otp   = '';

    /**
     * AcquireToken makes a POST request to obtain an access token.
     *
     * @return string The access token retrieved from the API.
     */
    public function acquireToken()
    {
        $resourcePath = "/auth/token";
        $url = $this->host . $resourcePath;

        $headers = [
            'Content-Type: application/x-www-form-urlencoded',
            'Authorization-Key: ' . $this->apiKey
        ];

        $post = "grant_type=password&username=" . $this->userName . "&password=" . urlencode($this->password);
        // $post = "grant_type=password&username=" . $this->userName . "&password=" . urlencode($this->password)  . "&otp=" . urlencode($this->otp);

        $crl = curl_init();

        curl_setopt($crl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($crl, CURLOPT_URL, $url);
        curl_setopt($crl, CURLOPT_POST, 1);
        curl_setopt($crl, CURLOPT_POSTFIELDS, $post);
        curl_setopt($crl, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($crl);
        curl_close($crl);

        $tokenObj = json_decode($response);
        if (isset($tokenObj->access_token)) {
            $this->token = $tokenObj->access_token;
        }
        
        return $this->token;
    }

    /**
     * Generates a complete URL by replacing placeholders in the resource path.
     *
     * @param string $resourcePath The API resource path with optional placeholders.
     * @param array  $params       The parameters to replace in the resource path.
     *
     * @return string The complete URL.
     */
    public function getUrl($resourcePath, $params = [])
    {
        if (!empty($params)) {
            foreach ($params as $key => $item) {
                $resourcePath = str_replace($key, $item, $resourcePath);
            }
        }
        return $this->host . $resourcePath;
    }

    /**
     * Creates the header required for an API call.
     *
     * @param string $token The access token to include in the header.
     *
     * @return array The HTTP headers for the API call.
     */
    public function getHeader($token)
    {
        $headers = [
            'Content-length: 0',
            'Content-type: application/json',
            'Authorization: Bearer ' . $token,
            'Authorization-Key: ' . $this->apiKey
        ];
        return $headers;
    }
}
?>