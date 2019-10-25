<?php
/**
 * @copyright 2019 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);

namespace Web\Authentication;

use Aws\Credentials\Credentials;
use Aws\Signature\SignatureV4;
use Domain\Users\Entities\User;
use GuzzleHttp\Psr7\ServerRequest;
use Psr\Http\Message\RequestInterface;

class Auth
{
    const HMAC_ALGORITHM       = 'AWS4-HMAC-SHA256';
    const HMAC_SERVICE         = 'account_tracker';
    const HMAC_REGION          = 'bloomington';

    const HEADER_AUTHORIZATION = 'Authorization';
    const HEADER_KEY_NAME      = 'AccessKeyId';
    // Header containing the username that's attempting to make the request
    const HEADER_USERNAME      = 'AccountTrackerUsername';

    public static function getAuthenticatedUser(AuthenticationService $service): ?User
    {
        if ( isset($_SESSION['USER'])) {
            return $_SESSION['USER'];
        }

        $request = ServerRequest::fromGlobals();
        if (   self::isHMACRequest       ($request)
            && self::isValidHMACSignature($request)) {

            $user = $service->identify($request->getHeader(self::HEADER_USERNAME)[0]);
            if ($user) { return $user; }
        }
        return null;
    }

    public static function isAuthorized(string $routeName, ?User $user): bool
    {
        global $ZEND_ACL;

        list($resource, $permission) = explode('.', $routeName);
        $role = $user ? $user->role : 'Anonymous';

        return $ZEND_ACL->hasResource($resource)
            && $ZEND_ACL->isAllowed($role, $resource, $permission);
    }

    public static function isHMACRequest(RequestInterface $request): bool
    {
        return $request->hasHeader(self::HEADER_AUTHORIZATION)
            && $request->hasHeader(self::HEADER_KEY_NAME     )
            && $request->hasHeader(self::HEADER_USERNAME     )
            && substr($request->getHeader(self::HEADER_AUTHORIZATION)[0], 0, 16) == self::HMAC_ALGORITHM;
    }

    public static function isValidHMACSignature(RequestInterface $request): bool
    {
        $keys        = include SITE_HOME.'/api_keys.php';
        $accessKeyId = $request->getHeader(self::HEADER_KEY_NAME)[0];
        if (!array_key_exists($accessKeyId, $keys)) { return false; }

        $credentials = new Credentials($accessKeyId, $keys[$accessKeyId]);
        $signer      = new SignatureV4(self::HMAC_SERVICE, self::HMAC_REGION);
        $output      = $signer->signRequest($request, $credentials);

        return $output->getHeader(self::HEADER_AUTHORIZATION)[0] === $request->getHeader(self::HEADER_AUTHORIZATION)[0];
    }
}
