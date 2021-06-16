<?php

namespace ArticleBundle\Controller;


use ArticleBundle\ArticleBundle;
use ArticleBundle\Entity\Annonce;
use ArticleBundle\Entity\Statistique;
use ArticleBundle\Form\AnnonceType;
use ArticleBundle\Form\AnnonceUpdateType;
use ArticleBundle\Form\CategorieType;
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\Material\LineChart;
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\PieChart;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use ArticleBundle\Entity\Categorie;
use Symfony\Bundle\FrameworkBundle\Tests\Fixtures\Validation\Article;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\OptionsResolver\OptionsResolver;
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\Histogram;
use AppBundle\Entity\User;


class DefaultController extends Controller
{
    /**
     * @Route("/AjouterCategorie",name="AjouterCategorie")
     */
    public function createAction(Request $request)
    {
        $categorie = new Categorie();

        $form = $this->createForm(CategorieType::class, $categorie);

        $form = $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($categorie);
            $em->flush();
            return $this->redirectToRoute('ConsulterCategorie');
        }
        return $this->render('@Article/Default/AjouterCategorie.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("/ConsulterCategorie",name="ConsulterCategorie")
     */

    public function ConsulterAction()
    {
        $projets = $this->getDoctrine()->getRepository(Categorie::class)->findAll();
        return $this->render('@Article/Default/Categorie.html.twig', array(
            'projets' => $projets
        ));
    }

    /**
     * @Route("/UpdateCategorie{id}",name="UpdateCategorie")
     */

    public function UpdateCategorieAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $modele = $em->getRepository(Categorie::class)->find($id);
        if ($request->isMethod('POST')) {

            $modele->setType($request->get('type'));
            $em->flush();
            return $this->redirectToRoute('ConsulterCategorie');
        }

        return $this->render('@Article/Default/UpdateCategorie.html.twig', array(
            'modele' => $modele

        ));
    }

    /**
     * @Route("/deleteCategorie{id}",name="deleteCategorie")
     */
    public function deleteCategorieAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $Categorie = $em->getRepository(Categorie::class)->find($id);
        $em->remove($Categorie);
        $em->flush();
        return $this->redirectToRoute("ConsulterCategorie");
    }

    /**
     * @Route("/AjoutAnnonce",name="AjoutAnnonce")
     */

    public function AjoutAnnonceAction(Request $request)
    {
        $annonce = new Annonce();

        $form = $this->createForm(AnnonceType::class, $annonce);

        $form = $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $file =  $annonce->getPhoto();

            $fileName = md5(uniqid()).'.'.$file->guessExtension();

            $pathfile=$this->container->getParameter('pathmedia');


            $file->move(

                $pathfile,
                $fileName
            );
            $annonce->setPhoto($fileName);

            $annonce->setClientId($this->getUser()->getId());
            $em->persist($annonce);
            $em->flush();
            return $this->redirectToRoute('ConsulterAnnonce');
        }

        return $this->render('@Article/Default/AjoutAnnonce.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("/ConsulterAnnonce",name="ConsulterAnnonce")
     */

    public function ConsulterAnnonceAction()
    {
        $annonces = $this->getDoctrine()->getRepository(Annonce::class)->findAll();
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $u1 = $user->getId();
        return $this->render('@Article/Default/Annonce.html.twig', array(
            'annonces' => $annonces,'u1'=>$u1
        ));
    }

    /**
     * @Route("/ModifierAnnonce{id}",name="ModifierAnnonce")
     */

    public function ModifierAnnonceAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $annonce = $em->getRepository(Annonce::class)->find($id);

        $form = $this->createForm(AnnonceUpdateType::class, $annonce);

        $form = $form->handleRequest($request);

        if ($form->isValid()) {
            $em->persist($annonce);
            $em->flush();
            return $this->redirectToRoute('ConsulterAnnonce');
        }

        return $this->render('@Article/Default/ModifierAnnonce.html.twig', array(
            'form' => $form->createView()

        ));
    }

    /**
     * @Route("/SupprimerAnnonce{id}",name="SupprimerAnnonce")
     */

    public function SupprimerAnnonceAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $annonce = $em->getRepository(Annonce::class)->find($id);
        $em->remove($annonce);
        $em->flush();
        return $this->redirectToRoute("ConsulterAnnonce");
    }


    /**
     * @Route("/Annonces",name="Annonces")
     */

    public function AnnoncesAction()
    {
        $annonces = $this->getDoctrine()->getRepository(Annonce::class)->findAll();
        return $this->render('@Article/Default/Annonces.html.twig', array(
            'annonces' => $annonces
        ));
    }

    /**
     * @Route("/ConsulterAnnoncePersonnel",name="ConsulterAnnoncePesrsonnel")
     */

    public function AnnoncePersonnelAction()
    {
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $u1 = $user->getId();
        $annonces = $this->getDoctrine()->getRepository(Annonce::class)->Finddql($u1);
       ;
        return $this->render('@Article/Default/AnnoncePersonnel.html.twig', array(
            'annonces' => $annonces,'u1'=>$u1
        ));
    }

    /**
     * @Route("/Statistique",name="Statistique")
     */


    public function statAction(){
        $pieChart = new LineChart();
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $id = $user->getId();

        $journales=$this->getDoctrine()->getRepository(Annonce::class)->finddql($id);
        // $pieChart->getData()->setArrayToDataTable(

        // $aa="[['Task', 'Hours per Day']";
        $aa=array();
        array_push($aa,['Categorie', 'Nombre dannonce']);
        // array_push($aa,   ['Work',     11]);


        foreach ($journales as $journale ){
            array_push($aa,   [$journale->getCategorie()->format('M-d'),     $journale->getId()]);


        }
//$aa.="]";
        // );
//var_dump($aa);
//exit();
        $pieChart->getData()->setArrayToDataTable(

            $aa
        // }

        );

        $pieChart->getOptions()->setTitle('nombre annonce par categorie');
        $pieChart->getOptions()->setHeight(500);
        $pieChart->getOptions()->setWidth(700);
        $pieChart->getOptions()->getTitleTextStyle()->setBold(true);
        $pieChart->getOptions()->getTitleTextStyle()->setColor('#8489C3');
        $pieChart->getOptions()->getTitleTextStyle()->setItalic(true);
        $pieChart->getOptions()->getTitleTextStyle()->setFontName('Libre Franklin');
        $pieChart->getOptions()->getTitleTextStyle()->setFontSize(20);
        $pieChart->getOptions()->setBackgroundColor('#CCE7E6');





        return $this->render('@Article/Default/Statistique.html.twig', array('piechart' => $pieChart));
    }
    /**
     * @Route("/Search",name="Search")
     */

    public function searchAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $requestString = $request->get('q');
        $posts =  $em->getRepository('ArticleBundle:Annonce')->findEntitiesByString($requestString);
        $post =  $em->getRepository('ArticleBundle:Annonce')->findDescByString($requestString);
        $date =  $em->getRepository('ArticleBundle:Annonce')->findDateByString($requestString);
        if(!$posts) {
            $result['posts']['error'] = "Annonce Not found 😞 ";
        } else {
            $result['posts'] = $this->getRealEntities($posts);
        }
        if(!$post) {
            $result['posts']['error'] = "Annonce Not found 😞 ";
        } else {
            $result['posts'] = $this->getRealEntities($post);
        }
        if(!$date) {
            $result['posts']['error'] = "Annonce Not found 😞 ";
        } else {
            $result['posts'] = $this->getRealEntities($date);
        }
        return new Response(json_encode($result));


    }
    public function getRealEntities($posts){
        foreach ($posts as $posts){
            $realEntities[$posts->getId()] = [$posts->getPhoto(),$posts->getNomArticle(),];

        }
        foreach ($posts as $post) {
            $realEntities[$post->getId()] = [$post->getPhoto(), $post->getNomArticle(),];
        }
        foreach ($posts as $date) {
            $realEntities[$date->getId()] = [$date->getPhoto(), $date->getNomArticle(),];
        }
        return $realEntities;
    }

}
