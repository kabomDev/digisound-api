<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Serializer\SerializerInterface;

class EditUserPasswordController extends AbstractController
{
    private SerializerInterface $serializer;
    private EntityManagerInterface $em;
    private Security $security;
    private UserPasswordEncoderInterface $encoder;

    public function __construct(SerializerInterface $serializer, EntityManagerInterface $em, Security $security, UserPasswordEncoderInterface $encoder)
    {
        $this->serializer = $serializer;
        $this->em = $em;
        $this->security = $security;
        $this->encoder = $encoder;
    }

    public function __invoke(Request $request)
    {
        $user = $this->security->getUser();
        $data = json_decode($request->getContent(), true);

        if (!$user) {
            throw new \Exception("Vous devez être connecté");
        }

        if (!$this->encoder->isPasswordValid($user, $data['previousPassword'])) {
            throw new \Exception("l'ancien mot de passe n'est pas le bon");
        }

        if ($data['newPassword'] !== $data['confirmation']) {
            throw new \Exception("les mots de passe ne sont pas identiques");
        }

        $hash = $this->encoder->encodePassword($user, $data['newPassword']);
        $user->setPassword($hash);
        $this->em->flush();

        $json = $this->serializer->serialize($user, "json", ["groups" => "user:read"]);

        return new JsonResponse($json, 200, [], true);
    }
}
