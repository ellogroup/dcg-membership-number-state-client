<?php

namespace Dcg\Client\MembershipNumberState;

use Dcg\Client\MembershipNumberState\Config;
use Dcg\Client\MembershipNumberState\Utils\Api;
use Dcg\Client\MembershipNumberState\Utils\Dates;


class Client
{

    /**
     * @var Config
     */
    protected $config;

    /**
     * The default headers to use for any requests
     * @var array
     */
    protected $defaultHeaders = [];

    /**
     * The headers to use for any requests. Overwrites default headers.
     * @var array
     */
    protected $headers = [];

    public function __construct(?\Dcg\Config $config = null)
    {
        $this->config = $config ?: Config::getInstance();
        $this->defaultHeaders['Access-Token'] = $this->config->get('api_access_token');
    }

    /**
     * Get the full endpoint URL
     *
     * @param string $endPointName e.g. MembershipNumberStateActivateApiEndpoint
     * @return string
     * @throws Exception\ConfigValueNotFoundException
     * @throws \Exception
     */
    private function getEndPoint($endPointName)
    {
        $baseUrl = $this->config->get('api_base_url');

        switch ($endPointName) {
            case 'MembershipNumberStateActivateApiEndpoint':
                $ep = '/eagle-eye/activate';
                break;
            case 'MembershipNumberStateDeactivateApiEndpoint':
                $ep = '/eagle-eye/deactivate';
                break;
            case 'MembershipNumberStateUpdateExpiryDateApiEndpoint':
                $ep = '/eagle-eye/updateexpirydate';
                break;
            case 'MembershipNumberStateUnexpireMembershipApiEndpoint':
                $ep = '/eagle-eye/unexpire';
                break;
            default:
                throw new \Exception("unknown endpoint requested", 401);
        }
        return $baseUrl . $ep;
    }

    /**
     * Activate the membership numbers
     *
     * @param array $membershipData an array of membership number data. <code>[['membershipNumber' => '', 'expiryDate' => 'Y-m-d H:i:s'], ...]</code>
     * @return string
     * @throws Exception\ConfigValueNotFoundException
     * @throws \Exception
     */
    public function activate(array $membershipData)
    {
        foreach ($membershipData as $i => $data) {
            if (!isset($data['membershipNumber']) || !isset($data['expiryDate'])) {
                if (!Dates::validateDate($data['expiryDate'])) {
                    throw new \InvalidArgumentException("Invalid data provided");
                }
            }
        }
        $membershipNumberStateApiEndpoint = $this->getEndpoint('MembershipNumberStateActivateApiEndpoint');
        $requestResponse = Api::sendRequest($this->getHeaders(), $membershipNumberStateApiEndpoint, 'POST', $membershipData);

        return $requestResponse['successful'];
    }

    /**
     * Deactivate the membership numbers
     *
     * @param array $membershipData an array of membership number data. <code>[['membershipNumber' => '', 'expiryDate' => 'Y-m-d H:i:s'], ...]</code>
     * @return string
     * @throws Exception\ConfigValueNotFoundException
     * @throws \Exception
     */
    public function deactivate(array $membershipData) /*FIXME  WIP!*/
    {
        /* we are expecting data in the format:
        [ ['membershipNumber' => 'xx', 'expiryDate'=>'Y-m-d H:i:s']]
        */
        foreach ($membershipData as $i => $data) {
            if (!isset($data['membershipNumber']) || !isset($data['expiryDate'])) {
                if (!Dates::validateDate($data['expiryDate'])) {
                    throw new \InvalidArgumentException("Invalid data provided");
                }
            }
        }
        $membershipNumberStateApiEndpoint = $this->getEndPoint('MembershipNumberStateDeactivateApiEndpoint');
        $requestResponse = Api::sendRequest($this->getHeaders(), $membershipNumberStateApiEndpoint, 'POST', $membershipData);

        return $requestResponse['successful'];
    }

    public function updateExpiryDate(array $membershipData) /*Fixme WIP! */
    {
        /* we are expecting data in the format:
        [ ['membershipNumber' => 'xx', 'expiryDate'=>'Y-m-d H:i:s']]
        */
        foreach ($membershipData as $i => $data) {
            if (!isset($data['membershipNumber']) || !isset($data['expiryDate'])) {
                if (!Dates::validateDate($data['expiryDate'])) {
                    throw new \InvalidArgumentException("Invalid data provided");
                }
            }
        }
        $membershipNumberStateApiEndpoint = $this->getEndPoint('MembershipNumberStateUpdateExpiryDateApiEndpoint');
        $requestResponse = Api::sendRequest($this->getHeaders(), $membershipNumberStateApiEndpoint, 'POST', $membershipData);

        return $requestResponse['successful'];
    }

	public function unexpireMembership(array $membershipData) 
    { 
		/* we are expecting data in the format:
        [ ['membershipNumber' => 'xx', 'expiryDate'=>'Y-m-d H:i:s']]
        */
        foreach ($membershipData as $i => $data) {
            if (!isset($data['membershipNumber']) || !isset($data['expiryDate'])) {
                if (!Dates::validateDate($data['expiryDate'])) {
                    throw new \InvalidArgumentException("Invalid data provided");
                }
            }
        }
        $membershipNumberStateApiEndpoint = $this->getEndPoint('MembershipNumberStateUnexpireMembershipApiEndpoint');
        $requestResponse = Api::sendRequest($this->getHeaders(), $membershipNumberStateApiEndpoint, 'POST', $membershipData);
        return $requestResponse['successful'];
    }
    
    /**
     * Get the headers to use for requests
     * @return array
     */
    public function getHeaders() {
        return array_merge($this->defaultHeaders, $this->headers);
    }

    /**
     * Set the headers to use for requests. Replaces existing headers and if a default header exists with the same
     * name it will be overwritten.
     *
     * @param array $headers Key-Value array of headers to set.
     */
    public function setHeaders($headers) {
        $this->headers = $headers;
    }

}
