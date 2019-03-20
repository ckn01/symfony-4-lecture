<?php
/**
 * Created by PhpStorm.
 * User: faisaluje
 * Date: 01/03/19
 * Time: 11:27
 */

namespace App\Controller;

use App\Security\UserConfirmationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class DefaultController
 * @Route("/")
 */
class DefaultController extends AbstractController
{
    /**
     * @Route("/"), name="default_index"
     */
    public function index()
    {
        return $this->render('base.html.twig');
    }

    /**
     * @Route("/confrim-user/{token}", name="default_confirmation_token")
     */
    public function confirmUser(string $token, UserConfirmationService $userConfirmationService)
    {
        $userConfirmationService->confirmUser($token);

        return $this->redirectToRoute('app_default_index');
    }
}