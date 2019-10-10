<?php
/**
 * @copyright 2019 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);

use Aws\Credentials\Credentials;
use Aws\Signature\SignatureV4;
use GuzzleHttp\Psr7\ServerRequest;
use PHPUnit\Framework\TestCase;
use Web\Authentication\Auth;

class HMACTest extends TestCase
{
    protected $accessKeyId = 'test';
    protected $secret      = 'asdkljafkljqiowfjuiof';
    protected $username    = 'testUser';

    public function testAwsSignatureGeneration()
    {
        $request     = ServerRequest::fromGlobals();
                                    ->withHeader('AccessKeyId',$this->accessKeyId)
                                    ->withHeader('AccountTrackerUsername', $this->username);

        $credentials = new Credentials($this->accessKeyId, $this->secret);
        $signer      = new SignatureV4('account_tracker', 'bloomington');
        $output      = $signer->signRequest($request, $credentials);

        $this->assertTrue(Auth::isHMACRequest($output), 'AWS library did not generate expected request signature');
    }
}
