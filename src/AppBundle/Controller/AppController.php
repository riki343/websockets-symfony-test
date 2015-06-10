<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class AppController extends Controller {
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction() {
        $this->get('app.file_logger')->logEvent('new', 'dwadawd');
        return $this->render('@App/index.html.twig');
    }
}