<?php
/**
 * Created by PhpStorm.
 * User: faisaluje
 * Date: 01/03/19
 * Time: 11:27
 */

namespace App\Controller;

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
        return new JsonResponse([
           'action' => 'index',
           'time' => time()
        ]);
    }
}