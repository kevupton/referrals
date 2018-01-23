<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 22/01/2018
 * Time: 4:07 PM
 */

namespace Kevupton\Referrals;

use Illuminate\Database\Eloquent\Model;
use Kevupton\Ethereal\Traits\HasMemory;
use Kevupton\Referrals\Exceptions\InvalidReferCodeException;
use Kevupton\Referrals\Models\Code;

class ReferToken
{
    use HasMemory;

    const SESSION_NAME = 'referrals.referral_token';

    /**
     * @var null
     */
    private $token;

    public function __construct ($token = null)
    {
        $this->token = $token ?: session(self::SESSION_NAME);
    }

    /**
     * @return null
     */
    public function getToken ()
    {
        return $this->token;
    }

    /**
     * Gets the user from the referral token code.
     *
     * @return Model
     */
    public function getUser ()
    {
        return $this->memory('user', function () {
            /** @var Code $code */
            $code = Code::query()
                ->where('code', $this->token)
                ->first();

            return $code->getUser();
        });
    }

    /**
     * Sets the referral token
     *
     * @param $token
     */
    public function setToken ($token)
    {
        $this->clearMemory();
        session([self::SESSION_NAME => $token]);
        $this->token = $token;
    }

    /**
     * Checks whether or not the token is set
     *
     * @return bool
     */
    public function hasToken ()
    {
        return (bool)$this->token;
    }


    /**
     * @return Model
     * @throws InvalidReferCodeException
     */
    public function validate ()
    {
        if ($this->hasToken() && ($user = $this->getUser())) {
            return $user;
        }

        throw new InvalidReferCodeException('Invalid refer code provided');
    }

    /**
     * Determines whether the token is valid
     *
     * @return bool
     */
    public function isValid ()
    {
        try {
            $this->validate();
            return true;
        } catch (InvalidReferCodeException $e) {
            return false;
        }
    }
}