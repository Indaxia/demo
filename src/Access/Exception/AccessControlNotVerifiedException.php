<?php
namespace App\Access\Exception;

use App\Access\Model\UserVerificationStateInterface;

/**
 * If this is thrown yous have to check verification factors to know whan exactly is not verified
 * @see UserVerificationStateInterface
 */
class AccessControlNotVerifiedException extends AccessControlException {}