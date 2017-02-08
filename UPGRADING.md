# Upgrading

## 5.x to 6.x

When upgrading to 6.x it is important to know that this library now requires PHP 7.1 as a minimum version.

Because of the new minimum PHP version the old `AES256Encryptor` class has been removed since Mcrypt is no longer supported. If you want to keep using this library you'll need to write your own implementation of the `EncryptorInterface` or use the new `LaravelEncryptor` if you use the Laravel framework.
