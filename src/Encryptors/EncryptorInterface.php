<?php

namespace DoctrineEncrypt\Encryptors;

/**
 * Encryptor interface for encryptors
 *
 * @author Victor Melnik <melnikvictorl@gmail.com>
 */
interface EncryptorInterface
{
    /**
     * Must accept data and return encrypted data
     */
    public function encrypt(string $data): string;

    /**
     * Must accept data and return decrypted data
     */
    public function decrypt(string $data): string;
}
