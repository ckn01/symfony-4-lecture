<?php
/**
 * Created by PhpStorm.
 * User: faisaluje
 * Date: 14/03/19
 * Time: 14:04
 */

namespace App\Controller;

use ApiPlatform\Core\Validator\ValidatorInterface;
use App\Entity\User;

class ResetPasswordAction
{
    private $validator;

    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    public function __invoke(User $data)
    {
        // Like
        // $reset = new ResetPasswordAction();
        // $reset();
//        var_dump(array($data));die;

        $this->validator->validate($data);

        // Validator is only called after we return the data from this action!
    }
}