<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\FormationRepository;
use App\Repository\NiveauRepository;

/**
 * Description of FormationsController
 *
 * @author emds
 */
class FormationsController extends AbstractController {
    
    private const PAGEFORMATIONS = "pages/formations.html.twig";

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
     */
    function __construct(FormationRepository $repository, NiveauRepository $repositoryNiveau) {
        $this->repository = $repository;
        $this->repositoryNiveau = $repositoryNiveau;
    }

    /**
     * @Route("/formations", name="formations")
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
     * @Route("/formations/tri/{champ}/{ordre}", name="formations.sort")
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
     * @Route("/formations/recherche/{champ}", name="formations.findallcontain")
     * @param type $champ
     * @param Request $request
     * @return Response
     */
    public function findAllContain($champ, Request $request): Response{
        if($this->isCsrfTokenValid('filtre_'.$champ, $request->get('_token'))){
            $valeur = $request->get("recherche");
            $niveaux = $this->repositoryNiveau->findAll();
            $formations = $this->repository->findByContainValue($champ, $valeur);
            return $this->render(self::PAGEFORMATIONS, [
                'formations' => $formations,
                'niveaux' => $niveaux
            ]);
        }
        return $this->redirectToRoute("formations");
    }  
    
    /**
     * @Route("/formations/niveau/{champ}", name="formations.findByNiveau")
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
     * @Route("/formations/formation/{id}", name="formations.showone")
     * @param type $id
     * @return Response
     */
    public function showOne($id): Response{
        $formation = $this->repository->find($id);
        $niveaux = $this->repositoryNiveau->findAll();
        return $this->render("pages/formation.html.twig", [
            'formation' => $formation,
            'niveaux' => $niveaux
        ]);        
    }    
}
