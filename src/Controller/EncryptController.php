<?php

namespace App\Controller;

use Nzo\UrlEncryptorBundle\Annotations\ParamEncryptor;
use Nzo\UrlEncryptorBundle\Encryptor\Encryptor;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class EncryptController extends AbstractController
{
    /**
     * @Route("/encrypt/{text}", name="app_encrypt")
     * @ParamEncryptor(params={"text"})
     */
    public function Encrypt(string $texto): \Symfony\Component\HttpFoundation\JsonResponse
    {
        return $this->json([
            'url' => $this->generateUrl('app_decrypt', [
                'textEncrypted' => $texto
            ], UrlGeneratorInterface::ABSOLUTE_URL)
        ]);
    }

    /**
     * @Route("/decrypt/{textEncrypted}", name="app_decrypt")
     */
    public function Decrypt(string $textEncrypted, Encryptor $encryptor): \Symfony\Component\HttpFoundation\JsonResponse
    {
        $textoDesencriptado = $encryptor->decrypt($textEncrypted);
        return $this->json([
            'texto' => $textEncrypted
        ]);
    }
}
