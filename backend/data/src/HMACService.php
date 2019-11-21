<?php
/**
 * @copyright 2019 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);

namespace Site;

use COB\HttpSignature\Context;
use GuzzleHttp\Psr7;
use Web\Authentication\Auth;

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

    public function signRequest(Psr7\Request $request): Psr7\Request
    {
        $keys = include SITE_HOME.'/api_keys.php';

        $context = new Context($keys);
        $request = $request->withHeader(Auth::HEADER_USERNAME, $this->username);
        return $context->sign($request, $this->accessKeyId, Auth::HMAC_ALGORITHM, [Auth::HEADER_USERNAME]);
    }
}
