<?php

namespace ForumBundle\Controller;

use ForumBundle\Entity\avis;
use ForumBundle\Entity\Block;
use ForumBundle\Entity\Categorie;
use ForumBundle\Entity\Discussion;
use ForumBundle\Entity\Question;
use ForumBundle\Entity\Reponse;
use ForumBundle\Entity\Report;
use ForumBundle\Entity\Upvote;
use AppBundle\Entity\User;
use ForumBundle\Form\avisType;
use ForumBundle\Form\CategorieType;
use ForumBundle\Form\Question1Type;
use ForumBundle\Form\QuestionType1;
use ForumBundle\Form\DiscussionType;
use ForumBundle\Form\ReponseType;
use MessageBird\Client;
use MessageBird\Exceptions\HttpException;
use MessageBird\Exceptions\RequestException;
use MessageBird\Exceptions\ServerException;
use MessageBird\Objects\Message;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use ForumBundle\Entity\Question1;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;


class DefaultController extends Controller
{
    public function getUserAction($email)
    {
        $user = $this->getDoctrine()->getManager()->getRepository(User::class)->findOneBy(['email'=>$email]);
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($user);
        return new JsonResponse($formatted);
    }

    public function getQuestionAction()
    {
        $quest = $this->getDoctrine()->getManager()->getRepository(Question1::class)->findAll();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($quest);
        return new JsonResponse($formatted);
    }

    public function getrepAction()
    {
        $quest = $this->getDoctrine()->getManager()->getRepository(Reponse::class)->findAll();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($quest);
        return new JsonResponse($formatted);
    }

    public function getdescAction()
    {
        $quest = $this->getDoctrine()->getManager()->getRepository(Discussion::class)->findAll();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($quest);
        return new JsonResponse($formatted);
    }


    public function newquestionAction(Request $request)
    {
        $em=$this->getDoctrine()->getManager();
        $quest = new Question1();
        $user = $this->getDoctrine()->getManager()->getRepository(User::class)->findOneBy(['id'=>$request->get('id')]);
        $cat = $this->getDoctrine()->getManager()->getRepository(Categorie::class)->findOneBy(['id'=>$request->get('categ')]);
        $quest->setQuest($request->get('quest'));
        $quest->setDescr($request->get('descr'));
        $quest->setUser($user);
        $quest->setCategorie($cat);
        $quest->setNbRep(0);
        $quest->setNbrVue(0);
        $quest->setNbVue(0);
        $quest->setVote(0);
        $quest->setDate(new \DateTime());
        $em->persist($quest);
        $em->flush();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($quest);
        return new JsonResponse($formatted);
    }

    public function newdiscAction(Request $request)
    {
        $em=$this->getDoctrine()->getManager();
        $rep = new Discussion();
        $user = $this->getDoctrine()->getManager()->getRepository(User::class)->findOneBy(['id'=>$request->get('user_id')]);
        $quest = $this->getDoctrine()->getManager()->getRepository(Reponse::class)->findOneBy(['id'=>$request->get('id')]);
        $rep->setUser($user);
        $rep->setReponse($quest);
        $rep->setDescr($request->get('descr'));
        $em->persist($rep);
        $em->flush();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($quest);
        return new JsonResponse($formatted);
    }

    public function newanswerAction(Request $request)
    {
        $em=$this->getDoctrine()->getManager();
        $rep = new Reponse();
        $user = $this->getDoctrine()->getManager()->getRepository(User::class)->findOneBy(['id'=>$request->get('user_id')]);
        $quest = $this->getDoctrine()->getManager()->getRepository(Question1::class)->findOneBy(['id'=>$request->get('id')]);
        $rep->setUser($user);
        $rep->setQuestion($quest);
        $rep->setSolution("cancel");
        $rep->setDecr($request->get('quest'));
        $em->persist($rep);
        $em->flush();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($quest);
        return new JsonResponse($formatted);
    }

    public function readAction(Request $request)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $this->denyAccessUnlessGranted('ROLE_USER');

        $ques=$this->getDoctrine()->getManager()->getRepository(Question1::class)->findAll();
        $reponses=$this->getDoctrine()->getManager()->getRepository(Reponse::class)->findAll();
        $likes = $this->getDoctrine()->getManager()->getRepository(Upvote::class)->findAll();
        $categories = $this->getDoctrine()->getManager()->getRepository(Categorie::class)->findAll();
        $reports = $this->getDoctrine()->getManager()->getRepository(Report::class)->findAll();
        $paginator = $this->get('knp_paginator');
        $questions = $paginator->paginate(
            $ques,
            $request->query->getInt('page', 1),
            6
        );
        $question=new Question1();
        $form=$this->createForm(Question1Type::class,$question);
        $blocks = $this->getDoctrine()->getManager()->getRepository(Block::class)->findAll();
        $form=$form->handleRequest($request);
        if($form->isValid()){
            $test = false;
            foreach($blocks as $block){
                if($block->getUser() == $this->getUser()){
                    $date = new \DateTime() ;
                    if($block->getNbjours() < $date ){
                        $test = true;
                    }
                }
            }
            if($test == true){
               return $this->redirectToRoute("warning");
            }else{
                $em=$this->getDoctrine()->getManager();
                $user = $this->getUser();
                $question->setUser($user);
                $question->setDate(new \DateTime());
                $em->persist($question);
                $em->flush();
                return $this->redirectToRoute('read');
            }
        }
        return $this->render('@Forum/Forum/read.html.twig', array('categories'=>$categories,'likes'=>$likes,'reports'=>$reports,'questions'=>$questions , 'reponses'=>$reponses , 'form'=>$form->createView()));
    }

    public function readreadAction(Request $request ,$categ)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $this->denyAccessUnlessGranted('ROLE_USER');
        $reponses=$this->getDoctrine()->getManager()->getRepository(Reponse::class)->findAll();
        $likes = $this->getDoctrine()->getManager()->getRepository(Upvote::class)->findAll();
        $categories = $this->getDoctrine()->getManager()->getRepository(Categorie::class)->findAll();
        $categorie = $this->getDoctrine()->getManager()->getRepository(Categorie::class)->findOneBy(['description'=>$categ]);
        $ques=$this->getDoctrine()->getManager()->getRepository(Question1::class)->findBy(['categorie'=>$categorie]);
        $reports = $this->getDoctrine()->getManager()->getRepository(Report::class)->findAll();

        $paginator = $this->get('knp_paginator');
        $questions = $paginator->paginate(
            $ques,
            $request->query->getInt('page', 1),
            6
        );

        $question=new Question1();
        $form=$this->createForm(Question1Type::class,$question);
        $form=$form->handleRequest($request);
        if($form->isValid()){
            $em=$this->getDoctrine()->getManager();
            $user = $this->getUser();
            $question->setUser($user);
            $question->setDate(new \DateTime());
            $em->persist($question);
            $em->flush();
            return $this->redirectToRoute('read');
        }
        return $this->render('@Forum/Forum/readread.html.twig', array('categories'=>$categories,'likes'=>$likes,'reports'=>$reports,'questions'=>$questions , 'reponses'=>$reponses , 'form'=>$form->createView()));
    }


    public function readmyAction(Request $request)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $this->denyAccessUnlessGranted('ROLE_USER');

        $ques=$this->getDoctrine()->getManager()->getRepository(Question1::class)->findAll();
        $reponses=$this->getDoctrine()->getManager()->getRepository(Reponse::class)->findAll();
        $likes = $this->getDoctrine()->getManager()->getRepository(Upvote::class)->findAll();
        $reports = $this->getDoctrine()->getManager()->getRepository(Report::class)->findAll();

        $paginator = $this->get('knp_paginator'); //initialisation paginator
        $questions = $paginator->paginate(
            $ques,
            $request->query->getInt('page', 1),
            6
        );

        $question=new Question1();
        $form=$this->createForm(Question1Type::class,$question);
        $form=$form->handleRequest($request);
        if($form->isValid()){
            $em=$this->getDoctrine()->getManager();
            $user = $this->getUser();
            $question->setUser($user);
            $question->setDate(new \DateTime());
            $em->persist($question);
            $em->flush();
            return $this->redirectToRoute('read');
        }
        return $this->render('@Forum/Forum/readmy.html.twig', array('likes'=>$likes,'reports'=>$reports,'questions'=>$questions , 'reponses'=>$reponses , 'form'=>$form->createView()));
    }
    public function readrepAction(Request $request , $id)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $this->denyAccessUnlessGranted('ROLE_USER');
        $reponse= new Reponse();
        $em=$this->getDoctrine()->getManager();
        $question=$this->getDoctrine()->getManager()->getRepository(Question1::class)->find($id);
        $avis=$this->getDoctrine()->getManager()->getRepository(avis::class)->findAll();

        $nbr= $question->getNbrVue();
        $question->setNbrVue($nbr+1);
        $em->persist($question);
        $em->flush();
        $reps=$this->getDoctrine()->getManager()->getRepository(Reponse::class)->findAll();
        $discs=$this->getDoctrine()->getManager()->getRepository(Discussion::class)->findAll();
        $form=$this->createForm(ReponseType::class,$reponse);
        $form->handleRequest($request);
        $user = $this->getUser();
        if ($form->isValid()){
            $user = $this->getUser();
            $reponse->setUser($user);
            $reponse->setSolution('non');
            $reponse->setQuestion($question);
            $em->persist($reponse);
            $em->flush();
            return $this->redirectToRoute("readrep",['id'=>$id]) ;
        }
        return $this->render('@Forum/Forum/readrep.html.twig', array('avis'=>$avis , 'u'=> $user , 'reps'=>$reps , 'id'=>$id , 'question' => $question , 'form'=>$form->createView() , 'discs'=> $discs));
    }

    public function ajouteravisAction(Request $request , $id)
    {
        $em=$this->getDoctrine()->getManager();
        $reponse=$em->getRepository(Reponse::class)->find($id);
        $avi=$this->getDoctrine()->getManager()->getRepository(avis::class)->findAll();
        $avis=new Avis();
        $form=$this->createForm(avisType::class,$avis);
        $form->handleRequest($request);
        if($form->isSubmitted()) {
            $avis->setReponse($reponse);
            $em->persist($avis);
            $em->flush();
            return $this->redirectToRoute("read") ;

        }
        return $this->render('@Forum/Forum/avis.html.twig', array('form' => $form->createView() ,
            'i'=>$reponse , 'avis'=>$avi));
    }
    public function createAction(Request $request)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $this->denyAccessUnlessGranted('ROLE_USER');

        $question=new Question1();
        $form=$this->createForm(Question1Type::class,$question);
        $form=$form->handleRequest($request);
        if($form->isValid()){

            $em=$this->getDoctrine()->getManager();
            $user = $this->getUser();
            $question->setUser($user);
            $question->setDate(new \DateTime());
            $em->persist($question);
            $em->flush();
            return $this->redirectToRoute('read');
        }
        return $this->render('@Forum/Forum/create.html.twig', array('form'=>$form->createView()));
    }


    public function readdAction(Request $request , $id)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $this->denyAccessUnlessGranted('ROLE_USER');

        $discussion= new Discussion();
        $em=$this->getDoctrine()->getManager();
        $reponse=$this->getDoctrine()->getManager()->getRepository(Reponse::class)->find($id);
        $em->persist($reponse);
        $em->flush();
        $discs=$this->getDoctrine()->getManager()->getRepository(Discussion::class)->findAll();
        $avi=$this->getDoctrine()->getManager()->getRepository(avis::class)->findAll();
        $form=$this->createForm(DiscussionType::class,$discussion);
        $form->handleRequest($request);
        if ($form->isValid()){
            $user = $this->getUser();
            $discussion->setUser($user);
            $discussion->setReponse($reponse);
            $em->persist($discussion);
            $em->flush();
            return $this->redirectToRoute("readd",['id'=>$id]);
        }
        return $this->render('@Forum/Forum/readd.html.twig', array('discs'=>$discs, 'id'=>$id ,
            'i' => $reponse , 'form'=>$form->createView(),'avis'=>$avi));
    }

    public function updateAction(Request $request,$id){

        $question=$this->getDoctrine()->getRepository(Question1::class)->find($id);
        $form=$this->createForm(QuestionType1::class,$question);
        $form->handleRequest($request);
        if ($form->isValid()){
            $em=$this->getDoctrine()->getManager();
            $em->persist($question);
            $em->flush();
            return $this->redirectToRoute("read");
        }
        return $this->render("@Forum/Forum/update.html.twig",array('form'=>$form->createView()));
    }

    public function updatedAction(Request $request,$id){

        $discussion=$this->getDoctrine()->getRepository(Discussion::class)->find($id);
        $form=$this->createForm(DiscussionType::class,$discussion);
        $form->handleRequest($request);
        if ($form->isValid()){
            $em=$this->getDoctrine()->getManager();
            $em->persist($discussion);
            $em->flush();
            return $this->redirectToRoute("read",['id'=>$id]);
        }
        return $this->render("@Forum/Forum/update.html.twig",array('form'=>$form->createView()));
    }

    public function updaterepAction(Request $request,$id)

    {
        $reponse=$this->getDoctrine()->getRepository(Reponse::class)->find($id);
        $form=$this->createForm(ReponseType::class,$reponse);
        $form->handleRequest($request);
        if ($form->isValid()){
            $em=$this->getDoctrine()->getManager();
            $em->persist($reponse);
            $em->flush();
            return $this->redirectToRoute("read",['id'=>$id]);
        }
        return $this->render("@Forum/Forum/update.html.twig",array('form'=>$form->createView()));
    }

    public function deleteAction($id)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $this->denyAccessUnlessGranted('ROLE_USER');

        $em = $this->getDoctrine()->getManager();
        $question= $em->getRepository(Question1::class)->find($id);
        $em->remove($question);
        $em->flush();
        return $this->redirectToRoute("read");
    }

    public function deleterepAction($id)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $this->denyAccessUnlessGranted('ROLE_USER');

        $em = $this->getDoctrine()->getManager();
        $reponse= $em->getRepository(Reponse::class)->find($id);
        $em->remove($reponse);
        $em->flush();
        return $this->redirectToRoute('read');
    }

    public function deletedAction($id)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $this->denyAccessUnlessGranted('ROLE_USER');

        $em = $this->getDoctrine()->getManager();
        $discussion= $em->getRepository(Discussion::class)->find($id);
        $em->remove($discussion);
        $em->flush();
        return $this->redirectToRoute('read');
    }

    public function reportAction($id)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $this->denyAccessUnlessGranted('ROLE_USER');

        $em = $this->getDoctrine()->getManager();
        $question= $em->getRepository(Question1::class)->find($id);
            $r= new Report();
            $nbrep = $question->getNbRep();
            $question->setNbRep($nbrep+1);
            $em->persist($question);
            $r->setQuestion($question);
            $usr= $this->getUser();
            $r->setUser($usr);
            $em->persist($r);
            $em->flush();
        return $this->redirectToRoute("read");
    }

    public function unreportAction($id)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $this->denyAccessUnlessGranted('ROLE_USER');

        $em = $this->getDoctrine()->getManager();
        $question= $em->getRepository(Question1::class)->find($id);
        $r= new Report();
        $nbrep = $question->getNbRep();
        $question->setNbRep($nbrep+1);
        $em->persist($question);
        $report = $this->getDoctrine()->getRepository(Report::class)->findOneBy(['question'=>$question ,'user'=>$this->getUser()]);
        $em->remove($report);
        $em->flush();
        return $this->redirectToRoute("read");
    }

    public function likeMAction(Request $request)
        {
        $em = $this->getDoctrine()->getManager();
        $question=$this->getDoctrine()->getRepository(Question1::class)->find($request->get('id'));
        $user = $this->getDoctrine()->getManager()->getRepository(User::class)->find(['id'=>$request->get('user_id')]);
        $nb = $question->getVote();
        $question->setVote($nb+1);
        $em->persist($question);
        $l= new Upvote();
        $l->setQuestion($question);
        $usr= $this->getUser();
        $l->setUser($user);
        $em->persist($l);
        $em->flush();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($question);
        return new JsonResponse($formatted);
        }

    public function unlikeMAction(Request $request)
        {
        $em = $this->getDoctrine()->getManager();
        $question=$this->getDoctrine()->getRepository(Question1::class)->find($request->get('id'));
        $user = $this->getDoctrine()->getManager()->getRepository(User::class)->find(['id'=>$request->get('user_id')]);
        $nb = $question->getVote();
        $question->setVote($nb-1);
        $em->persist($question);
        $vote = $this->getDoctrine()->getRepository(Upvote::class)->findOneBy(['question'=>$question ,'user'=>$user]);
        $em->remove($vote);
        $em->flush();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($question);
        return new JsonResponse($formatted);
        }

    public function likeAction(Request $request , $id)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $this->denyAccessUnlessGranted('ROLE_USER');
        $em = $this->getDoctrine()->getManager();
        $question=$this->getDoctrine()->getRepository(Question1::class)->find($id);
            $nb = $question->getVote();
            $question->setVote($nb+1);
            $em->persist($question);
            $l= new Upvote();
            $l->setQuestion($question);
            $usr= $this->getUser();
            $l->setUser($usr);
            $em->persist($l);
            $em->flush();
        return $this->redirectToRoute("read",['id'=>$id]);
    }

    public function unlikeAction(Request $request , $id)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $this->denyAccessUnlessGranted('ROLE_USER');
        $em = $this->getDoctrine()->getManager();
        $question=$this->getDoctrine()->getRepository(Question1::class)->find($id);
        $nb = $question->getVote();
        $question->setVote($nb-1);
        $em->persist($question);
        $vote = $this->getDoctrine()->getRepository(Upvote::class)->findOneBy(['question'=>$question ,'user'=>$this->getUser()]);
        $em->remove($vote);
        $em->flush();
        return $this->redirectToRoute("read",['id'=>$id]);
    }


    public function barAction($id)
    {
        $MessageBird = new Client('RgF9xvn981cbTg1OqJCzxLV0A');
        $Message = new Message();
        $Message->originator = "HForum";
        $Message->recipients = array(+21693173067);
        $Message->body = 'félicitations, votre Question a passé 100 jaime dans notre forum';

        try {
            $MessageBird->messages->create($Message);
        } catch (HttpException $e) {
        } catch (RequestException $e) {
        } catch (ServerException $e) {
        }
        return $this->redirectToRoute("like",['id'=>$id]);
    }

    public function solutionAction(Request $request,$id)

    {
        $em = $this->getDoctrine()->getManager();
        $reponse=$this->getDoctrine()->getRepository(Reponse::class)->find($id);
        $reponse->setSolution('solution');
        $em->persist($reponse);
        $em->flush();
            return $this->redirectToRoute("read");
    }

    public function csolutionAction(Request $request,$id)

    {
        $em = $this->getDoctrine()->getManager();
        $reponse=$this->getDoctrine()->getRepository(Reponse::class)->find($id);
        $reponse->setSolution('cancel');
        $em->persist($reponse);
        $em->flush();
        return $this->redirectToRoute("read");
    }

    public function warningAction()

    {
        $blocks = $this->getDoctrine()->getManager()->getRepository(Block::class)->findAll();
        return $this->render('@Forum/Forum/block.html.twig',array('blo'=>$blocks));
    }

}
