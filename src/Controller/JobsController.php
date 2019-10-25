<?php

namespace App\Controller;

use App\Entity\Job;
use App\Form\JobType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class JobsController extends AbstractController
{
    /**
     * @Route("/jobs", methods={"GET"})
     */
    public function index()
    {
        $encoders = [new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];

        $serializer = new Serializer($normalizers, $encoders);

        $repository = $this->getDoctrine()->getRepository(Job::class);

        $data = $repository->findBy(['user' => $this->getUser()]);

        $serializer->serialize($data, 'json');

        return $this->json($data);
    }

    /**
     * @Route("/jobs", methods={"POST"})
     */
    public function save(Request $request)
    {
        $data = \json_decode($request->getContent());

        $job = new Job();
        $job->setUser($this->getUser())
            ->setTitle($data->title)
            ->setCompany($data->company)
            ->setLink($data->link)
            ->setStatus('applied')
            ->setCreatedAt(new \DateTime('now'))
            ->setUpdatedAt(new \DateTime('now'));

        $doctrine = $this->getDoctrine()->getManager();
        $doctrine->persist($job);
        $doctrine->flush();

        return $this->json(["id" => $job->getId()]);
    }
}
