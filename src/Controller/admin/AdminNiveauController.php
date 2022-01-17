<?php

namespace App\Controller\admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\NiveauRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Niveau;

/**
 * Description of AdminNiveauController
 *
 * @author Jeremy
 */
class AdminNiveauController extends AbstractController{
    
    private const PAGEFORMATIONS = "admin/admin.niveaux.html.twig";
    
    /**
     *
     * @var EntityManagerInterface
     */
    private $om;
    /**
     *
     * @var NiveauRepository
     */
    private $repository;

    /**
     * 
     * @param NiveauRepository $repository
     * @param EntityManagerInterface $om
     */
    function __construct(NiveauRepository $repository, EntityManagerInterface $om) {
        $this->repository = $repository;
        $this->om = $om;
    }
    
    /**
     * @Route("/admin/niveaux", name="admin.niveaux")
     * @return Response
     */
    public function index(): Response{
        $niveaux = $this->repository->findAll();
        return $this->render(self::PAGEFORMATIONS, [
            'niveaux' => $niveaux
        ]);
    }
    
    /**
     * @Route("/admin/niveau/suppr/{id}", name="admin.niveau.suppr")
     * @param Niveau $niveau
     * @return Response
     */
    public function suppr(Niveau $niveau): Response{
        $this->om->remove($niveau);
        $this->om->flush();
        return $this->redirectToRoute('admin.niveaux');
    }
    
    /**
     * @Route("/admin/niveau/ajout", name="admin.niveau.ajout")
     * @param Request $request
     * @return Response
     */
    public function ajout(Request $request): Response{
        $nomNiveau = $request->get("nom");
        $niveau = new Niveau();
        $niveau->setDifficulter($nomNiveau);
        
        $this->om->persist($niveau);
        $this->om->flush();
        return $this->redirectToRoute('admin.niveaux');

    }
}
