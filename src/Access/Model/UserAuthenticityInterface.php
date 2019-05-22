<?php
namespace App\Access\Model;

interface UserAuthenticityInterface
{
    /**
     * Returns authentication data token to perform authentication comparison
     * @return string|null
     */
    public function getAuthenticator(): ?string;

    /**
     * Returns authentication data encoded as a plain string
     * Example: 
     *  - 'myEncryptedPassword', 
     *  - 'myEncryptedPassword~mySalt',
     *  - 'bcrypt:myEncryptedPassword',
     *  - '{"faceRecognition": {"algorithm":"OpenBR","data":"..."}}'
     *
     * @return string|null
     */
    public function getAuthenticationDataRaw(): ?string;

    /**
     * Returns parsed authentication data
     * Example: 
     *  - ['password' => 'myEncryptedPassword'], 
     *  - ['password' => 'myEncryptedPassword', 'salt' => 'mySalt'],
     *  - ['algorithm' => 'bcrypt', 'password' => 'myEncryptedPassword'],
     *  - ["faceRecognition" => ["algorithm" => "OpenBR", "data" => "..."]]
     *
     * @return string[] 
     */
    public function getAuthenticationData(): array;
}