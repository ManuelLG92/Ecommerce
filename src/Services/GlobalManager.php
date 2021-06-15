<?php


namespace App\Services;


use App\Entity\ContactFormDTO;
use App\Entity\CustomUser;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class GlobalManager extends AbstractController
{
    public static $successOperation = "success_message";

    public static  $failOperation = "fail_message";

    private $validator;
    function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    public function CheckErrorsOnValidation(ConstraintViolationListInterface $errorsValidation ): bool
    {
        $hasErrors = false;

            if (count($errorsValidation)>0){
                foreach ($errorsValidation as $error)
                {
                    $this->addFlash($this->getParameter(self::$failOperation),$error->getMessage());
                }
                $hasErrors = true;
            }
        return $hasErrors;
    }

    public function ValidatorCustomUser(CustomUser  $customUser): ConstraintViolationListInterface
    {
        return $this->validator->validate($customUser);
    }


}