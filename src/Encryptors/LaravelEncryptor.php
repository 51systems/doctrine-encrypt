<?php

namespace DoctrineEncrypt\Encryptors;

use Illuminate\Contracts\Encryption\Encrypter;

class LaravelEncryptor implements EncryptorInterface
{
    /**
     * @var \Illuminate\Contracts\Encryption\Encrypter
     */
    private $encrypter;

    public function __construct(Encrypter $encrypter)
    {
        $this->encrypter = $encrypter;
    }

    /**
     * Must accept data and return encrypted data.
     */
    public function encrypt(string $data): string
    {
        return $this->encrypter->encrypt($data);
    }

    /**
     * Must accept data and return decrypted data.
     */
    public function decrypt(string $data): string
    {
        return $this->encrypter->decrypt($data);
    }
}
