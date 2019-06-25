<?php
namespace App\Access\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use App\Access\Model\UserVerificationStateInterface;
use App\Access\Model\UserInterface;
use App\Access\Security\User\VerificationState\FactorInterface;
use App\Access\Exception\VerificationFactorException;
use App\Access\Exception\VerificationStateException;

/**
 * @ODM\EmbeddedDocument
 */
class UserVerificationState implements UserVerificationStateInterface
{
    /**
     * @var array[]
     * @ODM\Field(type="hash")
     */
    protected $factors = [];

    public function verify(UserInterface $user)
    {
        foreach($this->factors as $rawFactor) {
            /**
             * @var FactorInterface|mixed $factor
             */
            $factor = unserialize($rawFactor);
            if(! $factor instanceof FactorInterface) {
                throw new VerificationStateException('Verification factor is corrupted. Expected FactorInterface, got '.gettype($factor));
            }
            $factor->verify($user);
        }        
    }

    public function setFactor(FactorInterface $factor): UserVerificationStateInterface
    {
        $this->factors[get_class($factor)] = serialize($factor);
        return $this;
    }

    public function getFactor(string $class): ?FactorInterface
    {
        $rawFactor = $this->factors[$class] ?? null;
        if($rawFactor) {
            $factor = unserialize($rawFactor);
            if(! $factor instanceof FactorInterface) {
                throw new VerificationStateException('Verification factor is corrupted. Expected FactorInterface, got '.gettype($factor));
            }
            return $factor;
        }
        return null;
    }

    public function removeFactor(string $class): UserVerificationStateInterface
    {
        if(isset($this->factors[$class])) {
            unset($this->factors[$class]);
        }
        return $this;
    }
}