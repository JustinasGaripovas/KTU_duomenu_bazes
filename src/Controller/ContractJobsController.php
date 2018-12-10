<?php

namespace App\Controller;

use App\Entity\LdapUser;
use App\Repository\LdapUserRepository;
use App\Repository\StructureRepository;
use App\Repository\SubunitRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ContractJobsController
 * @package App\Controller
 * @IsGranted("ADMIN")
 */
class ContractJobsController extends AbstractController
{

    const ITEMS_PER_PAGE = 20;

    /**
     * @Route("/contract/jobs", name="contract_jobs")
     */
    public function index()
    {
        return $this->render('contract_jobs/index.html.twig', [
            'controller_name' => 'ContractJobsController',
        ]);
    }

    /**
     * @Route("/contract/jobs/structure/ajax", name="contract_jobs_structure_ajax")
     */
    public function ajaxAction(SubunitRepository $subunitRepository,LdapUserRepository $ldapUserRepository, StructureRepository $structureRepository, Request $request) {

        if ($request->isXmlHttpRequest() || $request->query->get('showJson') == 1) {
            $pages = $request->request->get('page');
            $activeArray= $request->request->get('active_array');


            $allUsers = array();

            if($activeArray!=null)
            foreach ($activeArray as $subunit)
            {
                $allUsers = array_merge($allUsers, $ldapUserRepository->findWithPages(self::ITEMS_PER_PAGE * $pages, self::ITEMS_PER_PAGE,(int)$subunit));
            }else{
                $allUsers = [];
            }

           // $allUsers = array_merge($activeArray, $ldapUserRepository->findWithPages(self::ITEMS_PER_PAGE * $pages, self::ITEMS_PER_PAGE,(int)$subunit));


            $jsonData = array();
            $idx = 0;
            foreach($allUsers as $item) {
                $temp = array(
                    'name' => $item->getName(),
                    'role' => $item->getRole()
                );
                $jsonData[$idx++] = $temp;
            }

            $jsonArray= array();
            //$jsonArray[0] =  floor($ldapUserRepository->findCount()[0][1]/self::ITEMS_PER_PAGE);
            $jsonArray[0] =  $activeArray;
            $jsonArray[1] = $jsonData;

            return new JsonResponse($jsonArray);
        } else {
            return $this->render('structure/winter_road_content.twig');
        }
    }



}
