<?php

/**
 * Class Saml
 *
 *
 *
 * @see       https://github.com/SAML-Toolkits/php-saml
 *
 * @author    Sven Olaf Oostenbrink <so.oostenbrink@gmail.com>
 * @license   http://opensource.org/licenses/GPL-2.0 GNU Public License, Version 2
 * @copyright Copyright © 2022 Sven Olaf Oostenbrink <so.oostenbrink@gmail.com>
 * @package   Phoundation\Accounts
 */


declare(strict_types=1);

namespace Plugins\Phoundation\Sso;

use LightSaml\Credential\KeyHelper;
use LightSaml\Credential\X509Certificate;
use LightSaml\Model\Context\SerializationContext;
use LightSaml\Model\Metadata\AssertionConsumerService;
use LightSaml\Model\Metadata\EntityDescriptor;
use LightSaml\Model\Metadata\KeyDescriptor;
use LightSaml\Model\Metadata\SpSsoDescriptor;
use LightSaml\Model\XmlDSig\SignatureWriter;
use LightSaml\SamlConstants;
use Phoundation\Accounts\Users\Interfaces\UserInterface;
use Phoundation\Filesystem\PhoDirectory;
use Phoundation\Filesystem\PhoFile;
use Phoundation\Web\Http\Url;
use RobRichards\XMLSecLibs\XMLSecurityKey;


class Saml
{
    /**
     * @var UserInterface $user
     */
    protected UserInterface $user;


    /**
     * Saml class constructor
     */
    public function __construct()
    {
    }


    public function test(): static
    {
        $entityDescriptor = new EntityDescriptor();
        $entityDescriptor
            ->setID(\LightSaml\Helper::generateID())
            ->setEntityID('http://some.entity.id')
        ;

        $entityDescriptor->addItem(
            $spSsoDescriptor = (new SpSsoDescriptor())->setWantAssertionsSigned(true)
        );

        $spSsoDescriptor->addKeyDescriptor(
            $keyDescriptor = (new KeyDescriptor())
                ->setUse(KeyDescriptor::USE_SIGNING)
                ->setCertificate(X509Certificate::fromFile(PhoFile::new(DIRECTORY_ROOT . 'config/saml/saml-public-key.crt')->getSource()))
        );

        $spSsoDescriptor->addAssertionConsumerService(
            $acs = (new AssertionConsumerService())
                ->setBinding(SamlConstants::BINDING_SAML2_HTTP_POST)
                ->setLocation(Url::new('/sso/saml/acs.html')->makeWww())
        );

        $context = new SerializationContext();
showdie($entityDescriptor->serialize($context->getDocument(), $context));
        return $this;
    }

    public function testCertificate(): static
    {
        $certificate = X509Certificate::fromFile(DIRECTORY_ROOT . 'config/saml/saml-public-key.crt');
        $privateKey = KeyHelper::createPrivateKey(DIRECTORY_ROOT . 'config/saml/saml-public-key.pem', '', true, XMLSecurityKey::RSA_SHA256);

        $message->setSignature(SignatureWriter::createByKeyAndCertificate($certificate, $privateKey));
        $context = new SerializationContext();

        showdie($message->serialize($context->getDocument(), $context));
        return $this;
    }
}
