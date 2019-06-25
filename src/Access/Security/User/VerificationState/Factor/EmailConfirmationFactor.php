<?php
use App\Access\Security\User\VerificationState\FactorInterface;
use App\Access\Exception\VerificationFactorException;

class EmailConfirmationFactor extends AbstractConfirmationFactor
{
    /**
     * @var bool
     */
    private $confirmed = false;

    /**
     * @param bool $confirmed
     * @param bool $generateTokens Set to false to disable automatic tokens generation
     */
    public function __construct(bool $confirmed = false, bool $generateTokens = true)
    {
        parent::__construct($generateTokens);
        $this->confirmed = $confirmed;
    }

    public function verify(UserInterface $user)
    {
        if(!$this->confirmed) {
            throw new VerificationFactorException('Email is not confirmed. Please check your inbox and follow the instructions.', $this);
        }
    }

	/**
	 * @return bool 
	 */
	public function isConfirmed(): bool
	{
		return $this->confirmed; 
	}
}