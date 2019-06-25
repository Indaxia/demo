<?php
namespace App\Access\Exception;

use App\Access\Security\User\VerificationState\FactorInterface;

class VerificationFactorException extends AccessException 
{
    /**
     * @var FactorInterface
     */
    private $factor;

    public function __construct(string $message, FactorInterface $factor)
    {
        parent::__construct($message);
        $this->factor = $factor;
    }

	/**
	 * @return FactorInterface 
	 */
	public function getFactor(): FactorInterface
	{
		return $this->factor; 
	}
}