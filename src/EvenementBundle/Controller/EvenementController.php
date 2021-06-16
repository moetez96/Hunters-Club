<?php

namespace EvenementBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use EvenementBundle\Entity\Evenement;
use GroupBundle\Entity\Groups;
use EvenementBundle\Entity\Participant;
use EvenementBundle\Entity\TypeEvents;
use AppBundle\Entity\User;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\RadioType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
//use Symfony\Component\Form\Extension\Core\Type\FloatType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\HttpFoundation\Request;
use Knp\Bundle\SnappyBundle\Snappy\Response\PdfResponse;

class EvenementController extends Controller
{
    public function AjouterEvenementAction(Request $request)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        
        $Evenement = new Evenement();
        $form = $this->createFormBuilder($Evenement)
        ->add('Nom',TextType::class,array('attr'=>array('class'=>'form-control col-md-6','style'=>'margin-bottom:15px;')))
        
        ->add('TypeEvents',EntityType::class,array(
            'class' => 'EvenementBundle:TypeEvents',
            'placeholder'=>'Selecter un Type',
            'choice_label' => 'Type', 
            'mapped'=>false  ,
            'multiple'=>false,'attr'=>array('class'=>'form-control col-md-6','style'=>'margin-bottom:15px;')         
        ))

        ->add('lieu',TextType::class,array('attr'=>array( 'class'=>'form-control col-md-6','style'=>'margin-bottom:15px;')))
        ->add('Latitude',TextType::class,array('attr'=>array( 'class'=>'form-control col-md-6','style'=>'margin-bottom:15px;')))
        ->add('Longitude',TextType::class,array('attr'=>array( 'class'=>'form-control col-md-6','style'=>'margin-bottom:15px;')))
           
        ->add('Description',TextareaType::class,array('attr'=>array( 'class'=>'form-control col-md-6','style'=>'margin-bottom:15px;'))) 
 
        ->add('Groupe',EntityType::class,array(
            'class' => 'GroupBundle:Groups',
            'placeholder'=>'Selecter un groupe',
            'choice_label' => 'nom', 
            'mapped'=>false  ,
            'multiple'=>false,'attr'=>array('class'=>'form-control col-md-6','style'=>'margin-bottom:15px;')         
        ))
        ->add('Date',DateType::class,array(

                'widget' => 'single_text',
                'attr' => array(
                    'class' => 'calendar'
                )))
        ->add('NomImage',FileType::class,array('attr'=>array( 'class'=>'form-control col-md-6','style'=>'margin-bottom:15px;'))) 
        ->getForm();   
        $em = $this->getDoctrine()->getEntityManager();

        $EventsTest = $em->getRepository('EvenementBundle:Evenement')->findBy(array(
           'user' => $this->getUser()->getId(),
        ));


        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $Evenement->setClientId($this->getUser()->getId());
            $Evenement->setNom($form['Nom']->getData());
            $Evenement->setDate($form['Date']->getData());
            $Evenement->setTypeEvents($form['TypeEvents']->getData());
            $Evenement->setlieu($form['lieu']->getData());
            $Evenement->setLatitude($form['Latitude']->getData());
            $Evenement->setLongitude($form['Longitude']->getData());
            $file =  $Evenement->getNomImage();

            $fileName = md5(uniqid()).'.'.$file->guessExtension();

            $pathfile=$this->container->getParameter('pathmedia');
           

            $file->move(

                $pathfile,
                $fileName
            );


            //$doctechnique->setFichier($fileName);



            $Evenement->setNomImage($fileName);
             $Evenement->UploadProfilePicture();
             $Evenement->setDescription($form['Description']->getData());
           

            $Evenement->setGroupe($form['Groupe']->getData());
            $Evenement->UploadProfilePicture();
            
           
            $em = $this->getDoctrine()->getEntityManager();

            $em->persist($Evenement);
            $em->flush();
           return $this->redirectToRoute('ConsulterMyEvenement'); 
           
        } 
        return $this->render('EvenementBundle:Default:AjouterEvenement.html.twig',array(
            'form' => $form->createView()
        ));

    }
    
    public function ConsulterEvenementAction()
    {  
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        
        $em = $this->getDoctrine()->getEntityManager();
        $E = $em->getRepository('EvenementBundle:Evenement')->findAll();
        $membres = $em->getRepository('GroupBundle:Membre')->findAll();
        $P = $em->getRepository('EvenementBundle:Participant')->findAll();
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $u1 = $user->getId();
        
        return $this->render('EvenementBundle:Default:ConsulterEvenement.html.twig',array(
            'E' => $E
        , 'membres'=> $membres , 'u1'=> $u1,'P'=>$P)) ;
        
    }
    public function getMyEvenementAction(){
        
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $this->denyAccessUnlessGranted('ROLE_USER');
       
        $em = $this->getDoctrine()->getEntityManager();
        $em = $this->getDoctrine()->getEntityManager();
        $Events = $em->getRepository('EvenementBundle:Evenement')->findBy(array(
            'ClientId' => $this->getUser()->getId()
        ));
    
        return $this->render('EvenementBundle:Default:MyEvenement.html.twig',array(
            'Events' => $Events
        ));
        var_dump($Events);die();
    }
    public function ModifierEvenementAction($id,Request $request){


        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        
        $em = $this->getDoctrine()->getEntityManager();
        $Evenement = $em->getRepository('EvenementBundle:Evenement')->find($id);
        $form = $this->createFormBuilder($Evenement)
        ->add('Nom',TextType::class,array('attr'=>array( 'class'=>'form-control col-md-6','style'=>'margin-bottom:15px;')))
        ->add('Date',DateType::class,array(

                'widget' => 'single_text',
                'attr' => array(
                    'class' => 'calendar'
                )))

        ->add('TypeEvents',EntityType::class,array(
            'class' => 'EvenementBundle:TypeEvents',
            'placeholder'=>'Selecter un Type',
            'choice_label' => 'Type', 
            'mapped'=>false  ,
            'multiple'=>false,'attr'=>array('class'=>'form-control col-md-6','style'=>'margin-bottom:15px;')         
        ))

       
        ->add('lieu',TextType::class,array('attr'=>array( 'class'=>'form-control col-md-6','style'=>'margin-bottom:15px;')))
           
        ->add('Description',TextareaType::class,array('attr'=>array( 'class'=>'form-control col-md-6','style'=>'margin-bottom:15px;'))) 
 
        ->add('Groupe',EntityType::class,array(
            'class' => 'GroupBundle:Groups',
            'placeholder'=>'Selecter un groupe',
            'choice_label' => 'nom', 
            'mapped'=>false  ,
            'multiple'=>false,'attr'=>array('class'=>'form-control col-md-6','style'=>'margin-bottom:15px;')         
        ))
       ->getForm();   
        $em = $this->getDoctrine()->getEntityManager();

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $Evenement->setClientId($this->getUser()->getId());
            $Evenement->setNom($form['Nom']->getData());
            $Evenement->setDate($form['Date']->getData());
            $Evenement->setTypeEvents($form['TypeEvents']->getData());
            $Evenement->setlieu($form['lieu']->getData());
            
            $Evenement->setDescription($form['Description']->getData());
           
            $Evenement->setGroupe($form['Groupe']->getData());

            
           
            $em = $this->getDoctrine()->getEntityManager();

            $em->persist($Evenement);
            $em->flush();
           return $this->redirectToRoute('ConsulterMyEvenement'); 
           
        } 
        return $this->render('EvenementBundle:Default:EditEvenement.html.twig',array(
            'form' => $form->createView()
        ));

    }
    public function SupprimerAction($id){
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        
        $em = $this->getDoctrine()->getEntityManager();
        $Evenement= $em->getRepository('EvenementBundle:Evenement')->find($id);
        $em->remove($Evenement);
        $em->flush();
        return $this->redirect($this->generateUrl('ConsulterMyEvenement'));
    }
    public function searchAction(request $request)
    {   
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
       
        $Evenement = new Evenement();
        $form = $this->createFormBuilder($Evenement)

            ->add('Groupe',EntityType::class,array(
                'class' => 'GroupBundle:Groups',
                'placeholder'=>'Selecter un groupe',
                'choice_label' => 'nom',
                'mapped'=>false  ,
                'multiple'=>false,'attr'=>array('class'=>'form-control col-md-6','style'=>'margin-bottom:15px;')
            ))

       
        ->getForm(); 
       $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
       $Evenement = $this->getDoctrine()->getRepository(Evenement::class)
        ->findBy(array('groupe'=>$Evenement ->getNom()));
        }
       else{
       $Evenement = $this->getDoctrine()->getRepository(Evenement::class)
      ->findAll();
       }
       return $this->render('EvenementBundle:Default:search.html.twig',array("form"=>$form->createView(),'Evenement'=>$Evenement));

}   
       
   public function AjouterTypeAction(Request $request)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        
        $TypeEvents = new TypeEvents();
        $form = $this->createFormBuilder($TypeEvents)
        ->add('Type',TextType::class,array('attr'=>array('class'=>'form-control col-md-6','style'=>'margin-bottom:15px;')))
        ->add('Date',DateType::class,array(

                'widget' => 'single_text',
                'attr' => array(
                    'class' => 'calendar'
                )))
        ->getForm();
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            
            $TypeEvents->setType($form['Type']->getData());
            $TypeEvents->setDate($form['Date']->getData());
            
           
            $em = $this->getDoctrine()->getEntityManager();

            $em->persist($TypeEvents);
            $em->flush();
           
           
        } 
        return $this->render('EvenementBundle:Default:AjouterType.html.twig',array(
            'form' => $form->createView()
        ));

    }

     public function consulterTypeAction()
    {  
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $em = $this->getDoctrine()->getEntityManager();
        $TypeEvents = $em->getRepository('EvenementBundle:TypeEvents')->findAll();
        return $this->render('EvenementBundle:Default:consulterType.html.twig',array(
            'TypeEvents' => $TypeEvents
        ));
        
    }

    public function SupprimerTypeAction($id){
      
        
        $em = $this->getDoctrine()->getEntityManager();
        $TypeEvents= $em->getRepository('EvenementBundle:TypeEvents')->find($id);
        $em->remove($TypeEvents);
        $em->flush();
        return $this->redirect($this->generateUrl('consulterType'));

    }
    public function ModifierTypeAction($id,Request $request){


        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        
        $em = $this->getDoctrine()->getEntityManager();
        $TypeEvents = $em->getRepository('EvenementBundle:TypeEvents')->find($id);
        $form = $this->createFormBuilder($TypeEvents)
        ->add('Type',TextType::class,array('attr'=>array( 'class'=>'form-control col-md-6','style'=>'margin-bottom:15px;')))
       
       ->getForm();   
        $em = $this->getDoctrine()->getEntityManager();

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            
            $TypeEvents->setType($form['Type']->getData());

            $em = $this->getDoctrine()->getEntityManager();

            $em->persist($TypeEvents);
            $em->flush();
           return $this->redirectToRoute('consulterType'); 
           
        } 
        return $this->render('EvenementBundle:Default:ModifierType.html.twig',array(
            'form' => $form->createView()
        ));

    }
     public function MaapAction()

    {   
        $em = $this->getDoctrine()->getEntityManager();
        $E = $em->getRepository('EvenementBundle:Evenement')->findAll();
        $membres = $em->getRepository('EvenementBundle:Membre')->findAll();
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $u1 = $user->getId();

        $events = $em->getRepository('EvenementBundle:Evenement')->findAll();
        return $this->render('EvenementBundle:Default:maap.html.twig',array('events'=>$events , 'E' => $E
        , 'membres'=> $membres , 'u1'=> $u1));
    }
    public function pdfAction($id)
    {
        $Evenement = $this->getDoctrine()->getManager()->getRepository(Evenement::class)->find($id);
        $html = $this->renderView('EvenementBundle:Default:pdf.html.twig' ,array('Evenement'=>
            $Evenement));

        return new PdfResponse(
            $this->get('knp_snappy.pdf')->getOutputFromHtml($html),
            'file.pdf'
        );
    }
    public function joinEventsAction($id){

        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $idUser = $user->getId();
        $Participant=new Participant();
        $Participant->setUser($user);
        $Rep =$this->getDoctrine()->getManager()->getRepository(Evenement::class);
        $Evenement=$Rep->find($id);
        $Participant->setEvenement($Evenement);
       // $Participant->setDateJoin(new \DateTime());
        $em=$this->getDoctrine()->getManager();
        $em->persist($Participant);
        $em->flush();
        return $this->redirectToRoute('ConsulterEvenement');

    }



     
   
}