<?php
/**
 * Created by PhpStorm.
 * User: faisaluje
 * Date: 22/03/19
 * Time: 11:28
 */

namespace App\Controller;


use App\Entity\Image;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Exception\ValidatorException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UploadImageAction
{
    private $formFactory;
    private $entityManager;
    private $validator;

    public function __construct(
        FormFactoryInterface $formFactory,
        EntityManagerInterface $entityManager,
        ValidatorInterface $validator
    )
    {
        $this->formFactory = $formFactory;
        $this->entityManager = $entityManager;
        $this->validator = $validator;
    }

    public function __invoke(Request $request)
    {
        // Create a new Image instance
        $image = new Image();
        // Validate the form
        $form = $this->formFactory->create(null, $image);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Persist the new Image entity
            $this->entityManager->persist($image);
            $this->entityManager->flush();

            $image->setFile(null);

            return $image;
        }

        // Uploading done for us in background by VichUploader

        // throw on validation exception, that means something went wrong during form validation
        throw new ValidatorException(
            $this->validator->validate($image)
        );
    }
}