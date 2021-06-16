<?php

namespace ForumBundle\Controller;

use ForumBundle\Entity\avis;
use ForumBundle\Entity\Block;
use ForumBundle\Entity\Discussion;
use ForumBundle\Entity\Question;
use ForumBundle\Entity\Question1;
use ForumBundle\Entity\remove;
use ForumBundle\Entity\Reponse;
use ForumBundle\Form\BlockType;
use ForumBundle\Form\DiscussionType;
use ForumBundle\Form\ReponseType;
use http\Client\Curl\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class AdminController extends Controller
{
    public function readaAction(Request $request)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $questions=$this->getDoctrine()->getManager()->getRepository(Question1::class)->findAll();
        $reponses=$this->getDoctrine()->getManager()->getRepository(Reponse::class)->findAll();

        return $this->render('@Forum/Forum/reada.html.twig', array('questions'=>$questions , 'reponses'=>$reponses ));
    }

    public function readrepaAction(Request $request , $id)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $reponse= new Reponse();
        $em=$this->getDoctrine()->getManager();
        $question=$this->getDoctrine()->getManager()->getRepository(Question1::class)->find($id);
        $avis=$this->getDoctrine()->getManager()->getRepository(avis::class)->findAll();
        $discs=$this->getDoctrine()->getManager()->getRepository(Discussion::class)->findAll();
        $nbr= $question->getNbrVue();
        $question->setNbrVue($nbr+1);
        $em->persist($question);
        $em->flush();
        $reps=$this->getDoctrine()->getManager()->getRepository(Reponse::class)->findAll();
        return $this->render('@Forum/Forum/readrepa.html.twig', array('avis'=>$avis,'reps'=>$reps , 'id'=>$id , 'question' => $question , 'discs'=> $discs));
    }

    public function readdaAction(Request $request , $id)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $discussion= new Discussion();
        $em=$this->getDoctrine()->getManager();
        $reponse=$this->getDoctrine()->getManager()->getRepository(Reponse::class)->find($id);
        $em->persist($reponse);
        $em->flush();
        $discs=$this->getDoctrine()->getManager()->getRepository(Discussion::class)->findAll();

        return $this->render('@Forum/Forum/readda.html.twig', array('discs'=>$discs, 'id'=>$id , 'reponse' => $reponse));
    }

    public function deleteaAction($id)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $em = $this->getDoctrine()->getManager();
        $question= $em->getRepository(Question1::class)->find($id);
        $user = $question->getUser();
        $em->remove($question);
        $em->flush();
        $remove = $em->getRepository(remove::class)->findOneBy(['user'=>$user]);
        if($remove != null){
            $remove->setNbdelete($remove->getNbdelete()+1);
            $em->persist($remove);
            $em->flush();
        }else{
            $rem = new remove();
            $rem->setUser($question->getUser());
            $rem->setNbdelete(1);
            $em->persist($rem);
            $em->flush();
        }
        return $this->redirectToRoute("reada");
    }

    public function deleterepaAction($id)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $em = $this->getDoctrine()->getManager();
        $question= $em->getRepository(Reponse::class)->find($id);
        $em->remove($question);
        $em->flush();
        return $this->redirectToRoute('read');
    }

    public function deletedaAction($id)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $em = $this->getDoctrine()->getManager();
        $question= $em->getRepository(Discussion::class)->find($id);
        $em->remove($question);
        $em->flush();
        return $this->redirectToRoute('read');
    }

    public function listrmAction(){
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $em = $this->getDoctrine()->getManager();
        $removes = $em->getRepository(remove::class)->findAll();
        $blocks = $em->getRepository(Block::class)->findAll();
        $questions=$this->getDoctrine()->getManager()->getRepository(Question1::class)->findAll();
        return $this->render('@Forum/Forum/listrm.html.twig', array('questions'=>$questions ,'removes'=>$removes , 'blocks'=>$blocks));
    }

    public function blockAction(Request $request , $id){
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository(\AppBundle\Entity\User::class)->find($id);
        $block = new Block();
        $form=$this->createForm(BlockType::class,$block);
        $form=$form->handleRequest($request);
        if($form->isValid()){
            $block->setUser($user);
            $nb = $block->getNbjours();
            $block->setNbjours(new \DateTime("+$nb days"));
            $em->persist($block);
            $em->flush();
            return $this->redirectToRoute('listrm');
        }

        return $this->render('@Forum/Forum/block.html.twig', array('form'=>$form->createView()));
    }

}
