<?php

namespace DoctrineEncrypt\Encryptors;

use Illuminate\Contracts\Encryption\Encrypter;

class LaravelEncryptor implements EncryptorInterface
{
    /**
     * @var Encrypter
     */
    private $encrypter;

    public function __construct(Encrypter $encrypter)
    {
        $this->encrypter = $encrypter;
    }

    /**
     * {@inheritdoc}
     */
    public function encrypt(string $data): string
    {
        return $this->encrypter->encrypt($data);
    }

    /**
     * {@inheritdoc}
     */
    public function decrypt(string $data): string
    {
        return $this->encrypter->decrypt($data);
    }
}
