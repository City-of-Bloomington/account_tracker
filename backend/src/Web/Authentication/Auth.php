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
    public static function getAuthenticatedUser(): ?User
    {
//         $keys        = include SITE_HOME.'/api_keys.php';
//         $accessKeyId = 'test';
//         $username    = $_SESSION['USER']->username;
//
//         $request     = ServerRequest::fromGlobals()
//                                     ->withHeader('AccessKeyId',$accessKeyId)
//                                     ->withHeader('AccountTrackerUsername', $_SESSION['USER']->username);
//
//         $credentials = new Credentials($accessKeyId, $keys[$accessKeyId]);
//         $signer      = new SignatureV4('account_tracker', 'bloomington');
//         $output      = $signer->signRequest($request, $credentials);
//
//         if (   self::isHMACRequest       ($output)
//             && self::isValidHMACSignature($output)) {
//             print_r($output);
//         }
//         exit();

        if ( isset($_SESSION['USER'])) {
            return $_SESSION['USER'];
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
        return $request->hasHeader('Authorization')
            && $request->hasHeader('AccessKeyId'  )
            && $request->hasHeader('AccountTrackerUsername')
            && substr($request->getHeader('Authorization')[0], 0, 16) == 'AWS4-HMAC-SHA256';
    }

    public static function isValidHMACSignature(RequestInterface $request): bool
    {
        $keys        = include SITE_HOME.'/api_keys.php';
        $accessKeyId = $request->getHeader('AccessKeyId')[0];

        $credentials = new Credentials($accessKeyId, $keys[$accessKeyId]);
        $signer      = new SignatureV4('account_tracker', 'bloomington');
        $output      = $signer->signRequest($request, $credentials);

        return $output->getHeader('Authorization')[0] === $request->getHeader('Authorization')[0];
    }
}
