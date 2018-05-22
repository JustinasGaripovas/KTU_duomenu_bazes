<?php

namespace App\Controller;

use App\Entity\Job;
use App\Entity\RoadSection;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Serializer\SerializerInterface;
use App\Repository\LdapUserRepository;

class SearchController extends Controller
{
    /**
     * @Route("/search", name="search")
     */

    public function searchForJobs(Request $request, SerializerInterface $serializer) {

        if(!$request->isXmlHttpRequest()){
            return $this->render('search/index.html.twig');
       }
            $results = [];
            $searchString = $request->get('term');
            $foundEntities = $this->getDoctrine()
                ->getRepository(Job::class)
                ->findBySearchField($searchString);
            if (!$foundEntities) {
                $results[] = ['value' => "No items there found in database"] ;
            }
            else {
                foreach ($foundEntities as $entity){
                    $results[] = [
                        'value' => $entity->getJobId()." ".$entity->getJobName(),
                        'job_id' => $entity->getJobId(),
                        'unit_of' => $entity->getJobQuantity(),
                    ];
                }
            }

            return $this->json($results);
        }

    /**
     * @Route("/search/road", name="search/road")
     */

    public function searchForRoadSection(LdapUserRepository $ldapUserRepository,Request $request, SerializerInterface $serializer) {

        if(!$request->isXmlHttpRequest()){
            return $this->render('search/index.html.twig');
        }
        $username = $ldapUserRepository->findUnitIdByUserName($this->getUser()->getUserName());
        $unit_id = $username->getUnit()->getUnitId();
        $subunit_id = $username->getSubunit()->getSubunitId();

        $results = [];
        $searchString = $request->get('term');
        $foundEntities = $this->getDoctrine()
            ->getRepository(RoadSection::class)
            ->findRoadByNameOrIdField($searchString, $unit_id, $subunit_id);
        if (!$foundEntities) {
            $results[] = ['value' => "No items there found in database"] ;
        }
        else {
            foreach ($foundEntities as $entity){
                $results[] = [
                    'value' => $entity->getSectionId()." ".$entity->getSectionName(),
                    'section_begin' => $entity->getSectionBegin(),
                    'section_end' => $entity->getSectionEnd()
                ];
            }
        }

        return $this->json($results);

    }

}
