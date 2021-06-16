<?php

namespace JournaleBundle\Controller;

use JournaleBundle\Entity\Bareme;
use EvenementBundle\Entity\Evenement;
use JournaleBundle\Entity\Journale;
use JournaleBundle\Form\BaremeType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;


class TrophyController extends Controller
{
    function ListeScoreAction (){
        $date=new \DateTime('now');
        $username= $this->getUser()->getId();
        $datedebut='2020-01-01';
        $datefin=$date->format('Y-m-d');
        $em = $this->getDoctrine()->getEntityManager();
        $nbreschasses= $em->getRepository('JournaleBundle:Journale')-> findbynbrechassePeriode($username , $datedebut,$datefin );
        $evenement = $em->getRepository('EvenementBundle:Evenement')->findAll();
        foreach ($evenement as $event) {

            $typeevent = $event->getTypeEvents()->getType();
        }
        // var_dump($event);


        $score=0;
        //var_dump($nbreschasses);

        foreach ($nbreschasses as $nbre){
            $s=$em->getRepository('JournaleBundle:Bareme')-> findbybareme($nbre['nbrechasse']);
            if($typeevent=='competition')
            {
                $score+=$s+3;
            }
            elseif ($typeevent=='rando')
            {
                $score+=$s+1;
            }
            elseif($typeevent!='rando' and $typeevent!='competition')
            {
                $score+=$s;
            }
            //   var_dump($s);


        }

        //var_dump($score);
        //exit();

        return $this->render("@Journale/Score/listeScore.html.twig", array('score' => $score,'nbreschasses'=>$nbreschasses));
    }

    function ListeALLScoreAction ()
    {
        $date = new \DateTime('now');
        $datedebut = '2020-01-01';
        $datefin = $date->format('Y-m-d');
        $em = $this->getDoctrine()->getManager();
        $journal = $em->getRepository(Journale::class)->findAll();

        $evenements = $em->getRepository(Evenement::class)->findAll();
        foreach ($evenements as $even) {
            $typeevent = $even->getTypeEvents()->getType();
            //var_dump($typeevent);

        }
        $em = $this->getDoctrine()->getManager();
        $nbreschasses = $em->getRepository('JournaleBundle:Journale')->findbynbrechassePeriodeAll($datedebut, $datefin,$typeevent);
        $ba = $em->getRepository(Bareme::class)->findAll();
        $evenement = $em->getRepository(Journale::class)->findAll();
        foreach ($evenement as $event) {
            $typeevent = $even->getTypeEvents()->getType();
            //var_dump($typeevent);

        }
        //var_dump($nbreschasses);


        //   var_dump($s);
        return $this->render("@Journale/Score/liste.html.twig", array('typeevent'=>$typeevent,'nbreschasses'=>$nbreschasses ));





    }

    public function createBaremeAction(Request $request)
    {   //create an object to store our data after the form submission
        $bareme=new Bareme();
        $form=$this->createForm(BaremeType::class,$bareme);
        $form=$form->handleRequest($request);
        if($form->isValid()){
            $em=$this->getDoctrine()->getManager();
            $em->persist($bareme);
            $em->flush();
            return $this->redirectToRoute('readBareme');
        }
        return $this->render('@Journale/Score/createBareme.html.twig', array(
            'form'=>$form->createView()
        ));

    }

    public function readBaremeAction()
    {
        $baremes=$this->getDoctrine()->getRepository(Bareme::class)->findAll();
        //add the list of clubs to the render function as input to be sent to the view
        return $this->render('@Journale/Score/readBareme.html.twig', array('baremes'=>$baremes ));
    }
    public function deleteBaremeAction($id)
    {

        $em = $this->getDoctrine()->getManager();
        $bareme= $em->getRepository(bareme::class)->find($id);
        $em->remove($bareme);
        $em->flush();
        return $this->redirectToRoute("readBareme");
    }
    public function updateBaremeAction(Request $request, $id)
    {
        $baremes= $this->getDoctrine()->getRepository(Bareme::class)
            ->find($id);

        $form= $this->createForm(BaremeType::class,$baremes);
        $form->handleRequest($request);
        if ($form->isSubmitted()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($baremes);

            $em->flush();


            return $this->redirectToRoute("readBareme");
        }
        return $this->render("@Journale/Score/updateBareme.html.twig",
            array("form"=>$form->createView()));
    }

}

