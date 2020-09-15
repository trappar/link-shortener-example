<?php

namespace App\Controller;

use App\Repository\LinkRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * @Route("/{code}", requirements={"code"="^(?!api).*"})
     * @param                $code
     * @param LinkRepository $repo
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index($code, LinkRepository $repo)
    {
        if ($code) {
            $link = $repo->findOneBy([
                'code' => $code
            ]);
            if ($link) {
                $link->addVisit();
                $em = $this->getDoctrine()->getManager();
                $em->persist($link);
                $em->flush();
                return new RedirectResponse($link->getUrl());
            }
        }

        return $this->render('default/index.html.twig', [
            'controller_name' => 'DefaultController',
        ]);
    }
}
