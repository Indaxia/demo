<?php
namespace App\Access\Model;

interface UserAuthenticityInterface
{
    /**
     * @param string|null $hash Containing full hashed authentication data token
     * Example: 
     *  - 'myEncryptedPassword', 
     *  - 'myEncryptedPassword~mySalt',
     *  - 'bcrypt:myEncryptedPassword',
     *  - '{"faceRecognition": {"algorithm":"OpenBR","data":"..."}}'
     */
    public function __construct(?string $hash);

    /**
     * Returns authentication data token to perform authentication comparison
     * @return string|null
     */
    public function getHash(): ?string;
}