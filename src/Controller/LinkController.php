<?php

namespace App\Controller;

use App\Entity\Link;
use App\Repository\LinkRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class LinkController
 * @package App\Controller
 * @Route("/api/link")
 */
class LinkController extends AbstractController
{
    /**
     * @var Serializer
     */
    private $serializer;
    /**
     * @var LinkRepository
     */
    private LinkRepository $repo;

    public function __construct(
        SerializerInterface $serializer,
        LinkRepository $repo
    ) {
        $this->serializer = $serializer;
        $this->repo       = $repo;
    }

    /**
     * @Route("", methods={"POST"})
     * @param ValidatorInterface $validator
     * @param Request            $request
     * @return JsonResponse
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     */
    public function create(ValidatorInterface $validator, Request $request)
    {
        $data = json_decode($request->getContent(), true);
        $request->request->replace($data);

        $link = new Link();
        $link->setUrl($request->request->get('url'));

        $desiredCode = $request->request->get('desiredCode');
        $desiredCode = strlen($desiredCode) < 5
            ? $this->generateCode()
            : preg_replace('~\s~', '-', $desiredCode);
        $link->setCode($desiredCode);

        $issues   = $validator->validate($link);
        if ($issues->count() !== 0) {
            $errors = [];
            foreach ($issues as $issue) {
                $errors[] = [
                    'path'  => $issue->getPropertyPath(),
                    'error' => $issue->getMessage()
                ];
            }
            $response = ['errors' => $errors];
        } else {
            $link->setSecret(sha1(uniqid("", true)));

            $em = $this->getDoctrine()->getManager();
            $em->persist($link);
            $em->flush();

            $response = $this->serialize($link, true);
        }

        return new JsonResponse($response);
    }

    /**
     * @Route("/{code}", methods={"GET"})
     * @param string $code
     * @return JsonResponse
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     */
    public function view(string $code)
    {
        /** @var Link $link */
        $link = $this->getDoctrine()
            ->getRepository(Link::class)
            ->findOneBy(['code' => $code]);

        if ($link) {
            return new JsonResponse($this->serialize($link));
        }

        return new JsonResponse(null, 404);
    }

    /**
     * @Route("/{code}", methods={"DELETE"})
     * @param string  $code
     * @param Request $request
     * @return JsonResponse
     */
    public function delete(string $code, Request $request)
    {
        $secret = $request->get('secret');

        if ($secret) {
            $link = $this->repo->findOneBy([
                'code' => $code,
                'secret' => $secret
            ]);

            if ($link) {
                $em = $this->getDoctrine()->getManager();
                $em->remove($link);
                $em->flush();

                return new JsonResponse(null, 200);
            }
        }

        return new JsonResponse(null, 404);
    }

    /**
     * @param Link $link
     * @param bool $private
     * @return array|\ArrayObject|bool|\Countable|float|int|string|\Traversable|null
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     */
    private function serialize(Link $link, $private = false)
    {
        $attributes = ['code', 'url', 'visits'];
        if ($private) {
            $attributes[] = 'secret';
        }

        return $this->serializer->normalize($link, null, [
            AbstractNormalizer::ATTRIBUTES => $attributes
        ]);
    }

    private function generateCode()
    {
        $characters = '123456789bcdfghjkmnpqrstvwxyzBCDFGHJKLMNPQRSTVWXYZ';
        $charCount  = strlen($characters) - 1;
        do {
            $code = '';
            while (strlen($code) < 6) {
                $code .= $characters[mt_rand(0, $charCount)];
            }
        } while ($this->codeExists($code) || preg_match('~^api~', $code));

        return $code;
    }

    private function codeExists($code)
    {
        return !empty($this->repo->findOneBy(['code' => $code]));
    }
}
