<?php
/**
 * @copyright 2019 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);

namespace Web\Authentication;

use Aws\Credentials\Credentials;
use Aws\Signature\SignatureV4;
use GuzzleHttp\Psr7;

abstract class HMACService
{
    protected $accessKeyId; // HMAC key name
    protected $secret;      // HMAC secret key
    protected $username;    // The username of the person making the request

    public function __construct(string $accessKeyId, string $secret, string $username)
    {
        $this->accessKeyId = $accessKeyId;
        $this->secret      = $secret;
        $this->username    = $username;
    }

    public function signedPostRequest(string $url, array $form_data): Psr7\Request
    {
        $headers  = array_merge($this->HMACHeaders(), ['Content-Type' => 'application/x-www-form-urlencoded']);
        $body     = Psr7\stream_for(http_build_query($form_data, '', '&'));

        $request  = new Psr7\Request('POST', $url, $headers, $body);
        return $this->signRequest($request);
    }

    protected function HMACHeaders(): array
    {
        return [
            Auth::HEADER_KEY_NAME => $this->accessKeyId,
            Auth::HEADER_USERNAME => $this->username
        ];
    }

    protected function signRequest(Psr7\Request $request): Psr7\Request
    {
        $credentials = new Credentials($this->accessKeyId, $this->secret);
        $signer      = new SignatureV4(Auth::HMAC_SERVICE, Auth::HMAC_REGION);
        return $signer->signRequest($request, $credentials);
    }
}
