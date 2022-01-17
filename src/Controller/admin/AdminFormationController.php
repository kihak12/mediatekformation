<?php
namespace App\Controller\admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\FormationRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Formation;
use App\Form\FormationType;
use App\Repository\NiveauRepository;

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */


/**
 * Description of AdminFormationController
 *
 * @author Jeremy
 */
class AdminFormationController extends AbstractController{
        
    private const PAGEFORMATIONS = "admin/admin.formations.html.twig";
    
    /**
     *
     * @var EntityManagerInterface
     */
    private $om;
    /**
     *
     * @var FormationRepository
     */
    private $repository;

    /**
     *
     * @var NiveauRepository
     */
    private $repositoryNiveau;
    
    
    /**
     * 
     * @param FormationRepository $repository
     * @param EntityManagerInterface $om
     */
    function __construct(FormationRepository $repository, EntityManagerInterface $om, NiveauRepository $repositoryNiveau) {
        $this->repository = $repository;
        $this->om = $om;
        $this->repositoryNiveau = $repositoryNiveau;
    }

    /**
     * @Route("/admin", name="admin.formations")
     * @return Response
     */
    public function index(): Response{
        $formations = $this->repository->findAll();
        $niveaux = $this->repositoryNiveau->findAll();
        return $this->render(self::PAGEFORMATIONS, [
            'formations' => $formations,
            'niveaux' => $niveaux
        ]);
    }
    
    /**
     * @Route("/admin/tri/{champ}/{ordre}", name="admin.sort")
     * @param type $champ
     * @param type $ordre
     * @return Response
     */
    public function sort($champ, $ordre): Response{
        $formations = $this->repository->findAllOrderBy($champ, $ordre);
        $niveaux = $this->repositoryNiveau->findAll();
        return $this->render(self::PAGEFORMATIONS, [
           'formations' => $formations,
           'niveaux' => $niveaux
        ]);
    }   
    
    /**
     * @Route("/admin/recherche/{champ}", name="admin.findallcontain")
     * @param type $champ
     * @param Request $request
     * @return Response
     */
    public function findAllContain($champ, Request $request): Response{
        if($this->isCsrfTokenValid('filtre_'.$champ, $request->get('_token'))){
            $valeur = $request->get("recherche");
            $formations = $this->repository->findByContainValue($champ, $valeur);
            $niveaux = $this->repositoryNiveau->findAll();
            return $this->render(self::PAGEFORMATIONS, [
                'formations' => $formations,
                'niveaux' => $niveaux
            ]);
        }
        return $this->redirectToRoute("admin.formations");
    }
    
        /**
     * @Route("/admin/niveau/{champ}", name="admin.findByNiveau")
     * @param type $champ
     * @param Request $request
     * @return Response
     */
    public function findByNiveau($champ, Request $request): Response{
        if($this->isCsrfTokenValid('filtre_'.$champ, $request->get('_token'))){
            $valeur = $request->get("rechercheNiveau");
            $formations = $this->repository->findNiveauByContainValue($champ, $valeur);
            $niveaux = $this->repositoryNiveau->findAll();
            return $this->render(self::PAGEFORMATIONS, [
                'formations' => $formations,
                'niveaux' => $niveaux
            ]);
        }
        return $this->redirectToRoute("formations");
    }  
    
    /**
     * @Route("/admin/suppr/{id}", name="admin.formations.suppr")
     * @param Formation $formation
     * @return Response
     */
    public function suppr(Formation $formation): Response{
        $this->om->remove($formation);
        $this->om->flush();
        return $this->redirectToRoute('admin.formations');
    }
    
    /**
     * @Route("/admin/edit/{id}", name="admin.formations.edit")
     * @param Formation $formation
     * @param Request $request
     * @return Response
     */
    public function edit(Formation $formation, Request $request): Response{
        $formFormation = $this->createForm(FormationType::class, $formation);
        
        $formFormation->handleRequest($request);
        if($formFormation->isSubmitted() && $formFormation->isValid()){
            $this->om->flush();
            return $this->redirectToRoute('admin.formations');
        }
        
        return $this->render('admin/admin.formation.edit.html.twig', [
            'formation' => $formation,
            'formformation' => $formFormation->createView()
        ]);
    }
    
    /**
     * @Route("/admin/ajout", name="admin.formation.ajout")
     * @param Request $request
     * @return Response
     */
    public function ajout(Request $request): Response{
        $formation = new Formation();
        $formFormation = $this->createForm(FormationType::class, $formation);
        
        $formFormation->handleRequest($request);
        if($formFormation->isSubmitted() && $formFormation->isValid()){
            $this->om->persist($formation);
            $this->om->flush();
            return $this->redirectToRoute('admin.formations');
        }
        
        return $this->render('admin/admin.formation.ajout.html.twig', [
            'formation' => $formation,
            'formformation' => $formFormation->createView()
        ]);
    }
    
    
}
