<?php
namespace App\Access\Security\User\VerificationState\Factor;

use App\Access\Security\User\VerificationState\FactorInterface;

/**
 * Implements abstract messenger confirmation factor, such as password reset, email change etc.
 */
abstract class AbstractConfirmationFactor implements FactorInterface 
{
    /**
     * @var string Default confirmation expiration
     * @see https://nvlpubs.nist.gov/nistpubs/SpecialPublications/NIST.SP.800-63a.pdf
     */
    const DEFAULT_EXPIRATION = 'PT10M'; 

    /**
     * @var \DateTime
     */
    private $requestedAt;
    
    /**
     * @var string Confirmation token to check when following the confirmation URL from the inbox, sms etc. (generated automatically)
     */
    private $token;

    /**
     * @var string User-friendly confirmation code to paste to some UI as an alternative to the URL (generated automatically)
     */
    private $code;

    /**
     * @param bool $generateTokens Set to false to disable automatic tokens generation
     */
    public function __construct(bool $generateTokens = true)
    {
        $this->code = $generateTokens ? strtoupper(bin2hex(openssl_random_pseudo_bytes(3))) : '';
        $this->token = $generateTokens ? strtoupper(bin2hex(openssl_random_pseudo_bytes(20))) : '';
        $this->requestedAt = new \DateTime();
    }
    
    public function isExpired(\DateInterval $expiration = null, \DateTime $now = null)
    {
        if(! $now) {
            $now = new \DateTime();
        }
        if(! $expiration) {
            $expiration = new \DateInterval(static::DEFAULT_EXPIRATION);
        }
        return $this->requestedAt->add($expiration) < $now;
    }

	/**
	 * @return \DateTime 
	 */
	public function getRequestedAt(): \DateTime
	{
		return $this->requestedAt; 
	}

	/**
	 * @param \DateTime $value 
	 * @return static
	 */
	public function setRequestedAt(\DateTime $value)
	{
		$this->requestedAt = $value;

		return $this;
	}

	/**
	 * @return string Confirmation token to check when following the confirmation URL from the inbox, sms etc.
	 */
	public function getToken(): string
	{
		return $this->token; 
	}

	/**
	 * @param string $value Confirmation token to check when following the confirmation URL from the inbox, sms etc.
	 * @return static
	 */
	public function setToken(string $value)
	{
		$this->token = $value;

		return $this;
	}

	/**
	 * @return string User-friendly confirmation code to paste to some UI as an alternative to the URL.
	 */
	public function getCode(): string
	{
		return $this->code; 
	}

	/**
	 * @param string $value User-friendly confirmation code to paste to some UI as an alternative to the URL.
	 * @return static
	 */
	public function setCode(string $value)
	{
		$this->code = $value;

		return $this;
	}
}