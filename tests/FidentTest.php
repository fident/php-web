<?php

namespace Fident\Tests\Web;

use Fident\Web\Fident;
use Fident\Web\FidentConfiguration;
use PHPUnit\Framework\TestCase;

class FidentTest extends TestCase
{
  protected function _buildFident()
  {
    $config = new FidentConfiguration();
    $config->setPublicKey(
      '-----BEGIN PUBLIC KEY-----
MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAxp+KB57h1ORvf2fwuVpq
QH78r5or8H3z+nXTqGSiEAXiIEtmAafaSpxpJC4GTyiA5t668+Y+tnoj0o4w58+k
+hdJxGDjsivetOkYfCGSg4LgWhoRrc0eJ/7oxkmFNgHJ2hDz+1Drh4pmYtG2mOvO
Mhpc6+U8dnM4R3crHbrtFYz0IPlc4gjc3JnSKCIXDPNKBx+/FmXsFzCZAQgvaII/
71ocbQ1392L2RWetVAZJy4na3UJsaJYDEQXnY1hxA/ccaw/pqi900sP0nBWn3KhC
CbpSJWmxERnAlaZswdA5oEsHU8H526jLWeNnW5B/gAu3TGCl+uEq+nPG7HByqvT9
6QIDAQAB
-----END PUBLIC KEY-----'
    );
    $config->setAesKey('1a2b3c4d5e6789101112131415161718');
    return new Fident($config);
  }

  protected function _testJwt()
  {
    return 'eyJhbGciOiJSUzI1NiIsInR5cCI6IkpXVCJ9.eyJwYXlsb2FkIjoiOUszSmF2eE8xTUZsQVVtSHR3bGp0eDIrWWozVTc2VXg4L0Q1OHNJRzhKbU5sR3JGZU5VRTE0b2F4QnByMEVQODg3ZlorZ0pQMkExa2NBVTkrQ1Z0bjdrOXZsK2lNSGc0c0thUlZPMEUxVS8zUzdVd1NJa1FKbWVxNUsxdDVZZ3ZscTJjSVJ0VU9MUkZkT2R5aSsrQUU4ZmV0Nlk3Q2drdkNzeGpUL3VxL1VQbTZqMTlQbFBzYTFYRUpjVXd5U2RScFFzQUFOMlpaUU45ZlVJMnUvdmV0bjBOWVp0SmZyOGhPdkpmbHlIbVJVekszZEs2WmdjaWpKNlI4VUd3dGt1WlhQNnNZNWw1SGtzMmQ2NHM2azd1aW5WZE9TbGprdUd4ZDNIVUk5aDd6WjNvSitnNHFtZng2S04vSFZ1YWpHV3hua2pndXZpK21TeWVyd3hPaUd4NU1mazd6NWcvT2xnNURLTWxJVDRWbUpiNFBzWHM0Vmp6bXA2YUZNQ2VsUnkwREM1eFJkZWJpaysvbTl2RFdYeUFGSkJid09kbU5NSEJUSUtuMmp0ZkxtUm9mY25Kbkt5NUErSloyRVcyS0xLWk5WNWd5aXJsNG42dnZqeE1FdUdjdHlDMG05SFZWeFdGTEFCb21CUU5La0ZvOWJNSmJORlp1eiszeXpFbjYzR3hvelYyVVZVdHRSMEJmUzhOaTAxdUFCVUR0eTg9IiwiYWNjb3VudF90eXBlIjoidXNlciIsImV4cCI6MTU1MDQyMDU3MCwiaWF0IjoxNTQ3ODI4NTcwLCJpc3MiOiJhY2NvdW50LmZvcnRpZmkubWU6NzE4MCIsInN1YiI6IkVGSURGSUFULVpHOFhRVFpMWS1NSVNDUi03TU1OTU45In0.DCtjNadZFDNCOpyljHYXP9K9R3FvZ9Evz2D0lolCy3IPPWBbbOThiHqeBUqsdbtM3WAVKyfppnFelkNl0I0u70rsZOmot47AA_F_uP1s983px99MhjmzdokdpEeEE_BgyAj9KqXEbNoWLrsZoM96egZUY2jpkEI1ArGOmc3Pe15PprGfyVXZL9V7JyNe9T7ZThm-D6w2xmLO4MhSRPyc4njI-cZ2fyP7L7Yoi8YNsgjSAfoR4N8iDlJBjniy_d0qVcWUWj2eJxk63e3H8qW8yU_C9eavpT3GhrJhF-HxLaoAXsBMBlxL2EnN7An_MbJ7dIREfj3Acl9Cxf271znoLA';
  }

  public function testSecret()
  {
    $this->assertInstanceOf(FidentConfiguration::class, $this->_buildFident()->getConfig());
  }

  public function testVerifyJwt()
  {
    $this->assertTrue($this->_buildFident()->verifyJwt($this->_testJwt()));
    $this->assertFalse($this->_buildFident()->verifyJwt('we' . $this->_testJwt()));
  }

  public function testDecodeJwtPayload()
  {
    $decoded = $this->_buildFident()->decodeJwtPayload($this->_testJwt());
    $this->assertEquals("account.fortifi.me:7180", $decoded->getIssuer());
    $this->assertEquals(1547828570, $decoded->getIssuedAt());
    $this->assertEquals(1550420570, $decoded->getExpiry());
    $this->assertEquals("user", $decoded->getAccountType());
    $this->assertEquals("EFIDFIAT-ZG8XQTZLY-MISCR-7MMNMN9", $decoded->getTokenId());
    $this->assertEquals("2ea16", $decoded->getUserAgent());
    $this->assertEquals("EFIDFIID-ZG8XQTZRO-MISCR-0DAU77S", $decoded->getIdentityId());
    $this->assertEquals("test@fident.io", $decoded->getUsername());
    $this->assertEquals(1, $decoded->getType());
    $this->assertFalse($decoded->isVerified());
    $this->assertFalse($decoded->hasMfa());

    $attr = $decoded->getAttributes()['firstname'];
    $this->assertEquals('EFIDFIID-ZG8XQTZP8-MISCR-0HLCC72', $attr->getId());
    $this->assertEquals('firstname', $attr->getKey());
    $this->assertEquals('Fident', $attr->getValue());

    $attr = $decoded->getAttributes()['lastname'];
    $this->assertEquals('Tester', $attr->getValue());
  }
}
