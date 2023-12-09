<?php

namespace App\Bridge;

use App\Models\User;
use Laravel\Passport\Bridge\AccessToken;
use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Signer\Key\InMemory;
use Lcobucci\JWT\Signer\Rsa\Sha256;
use League\OAuth2\Server\CryptKey;

class PassportAccessToken extends AccessToken
{

    private $privateKey;

    public function __toString()
    {
        return $this->convertToJWT()->toString();
    }

    public function setPrivateKey(CryptKey $privateKey)
    {
        $this->privateKey = $privateKey;
    }

    public function convertToJWT()
    {
        $shopDomain = User::find($this->getUserIdentifier())->appShop->shop;
        $jwtConfiguaration = Configuration::forAsymmetricSigner(
            new Sha256(),
            InMemory::plainText(
                $this->privateKey->getKeyContents(),
                $this->privateKey->getPassPhrase() ?? ''
            ),
            InMemory::plainText('empty', 'empty')
        );
        return $jwtConfiguaration->builder()
            ->permittedFor($this->getClient()->getIdentifier())
            ->identifiedBy($this->getIdentifier())
            ->issuedAt(new \DateTimeImmutable())
            ->canOnlyBeUsedAfter(new \DateTimeImmutable())
            ->expiresAt($this->getExpiryDateTime())
            ->relatedTo((string)$this->getUserIdentifier())
            ->withClaim('scopes', $this->getScopes())
            ->withClaim("shop_domain", $shopDomain)
            ->getToken(
                $jwtConfiguaration->signer(),
                $jwtConfiguaration->signingKey()
            );
    }
}
