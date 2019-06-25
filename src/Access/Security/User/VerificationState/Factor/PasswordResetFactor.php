<?php
use App\Access\Security\User\VerificationState\FactorInterface;

class PasswordResetFactor extends AbstractConfirmationFactor
{
    /**
     * @var bool
     */
    private $done;

    /**
     * @param bool $isDone Is password change done
     * @param bool $generateTokens Set to false to disable automatic tokens generation
     */
    public function __construct(bool $isDone = false, bool $generateTokens = true)
    {
        parent::__construct($generateTokens);
        $this->done = $isDone;
    }

    public function verify(UserInterface $user)
    {
        // always verified
    }

	/**
	 * @return bool 
	 */
	public function isDone(): bool
	{
		return $this->done; 
	}
}