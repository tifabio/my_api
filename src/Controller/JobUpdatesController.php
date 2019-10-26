<?php

namespace App\Controller;

use App\Entity\Job;
use App\Entity\JobUpdate;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class JobUpdatesController extends AbstractController
{
    /**
     * @Route("/jobs/{id}/updates", methods={"GET"})
     */
    public function index(SerializerInterface $serializer, int $id)
    {
        $repository = $this->getDoctrine()->getRepository(Job::class);

        $job = $repository->find($id);

        if(!$job) {
            return $this->json(['msg' => 'Job not found!'], 404);
        }

        if($job->getUser()->getId() !== $this->getUser()->getId()) {
            return $this->json(null, 403);
        }

        $repository = $this->getDoctrine()->getRepository(JobUpdate::class);

        $data = $repository->findBy(['job' => $job],['created_at' => 'DESC']);

        $serializedData = $serializer->serialize($data, 'json', ['groups' => ['rest']]);

        return JsonResponse::fromJsonString($serializedData);
    }

    /**
     * @Route("/jobs/{id}/updates", methods={"POST"})
     */
    public function save(int $id, Request $request)
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

        $jobUpdate = new JobUpdate();
        $jobUpdate->setJob($job)
                  ->setDescription($data->description)
                  ->setCreatedAt(new \DateTime('now'));

        $doctrine = $this->getDoctrine()->getManager();
        $doctrine->persist($jobUpdate);
        $doctrine->flush();

        return $this->json(['id' => $jobUpdate->getId()]);
    }
}
