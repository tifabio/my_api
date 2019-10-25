<?php

namespace App\Controller;

use App\Entity\Job;
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
        $serializer = new Serializer([new ObjectNormalizer()], [new JsonEncoder()]);

        $repository = $this->getDoctrine()->getRepository(Job::class);

        $data = $repository->findBy(['user' => $this->getUser()],['updated_at' => 'DESC']);

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

        return $this->json(['id' => $job->getId()]);
    }

    /**
     * @Route("/jobs/{id}", methods={"PUT"})
     */
    public function update(int $id, Request $request)
    {
        $data = \json_decode($request->getContent());

        $repository = $this->getDoctrine()->getRepository(Job::class);

        $job = $repository->find($id);

        if(!$job) {
            return $this->json(['msg' => 'Job not found!'], 404);
        }

        if($job->getUser()->getId() !== $this->getUser()->getId()) {
            return $this->json(null, 403);
        }

        $job->setTitle($data->title)
            ->setCompany($data->company)
            ->setLink($data->link)
            ->setStatus($data->status)
            ->setUpdatedAt(new \DateTime('now'));

        $doctrine = $this->getDoctrine()->getManager();
        $doctrine->merge($job);
        $doctrine->flush();

        return $this->json(['id' => $job->getId()]);
    }
}
