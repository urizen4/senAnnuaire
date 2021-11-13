<?php

namespace App\Controller;
use App\Entity\Utilisateur;
use App\Entity\Contact;
//use Doctrine\DBAL\Types\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
    /**
     * @Route("/contact", name="contact")
     */
    public function index(Request $request,EntityManagerInterface $manager)
    {
        $repo = $this->getDoctrine()->getRepository(Contact::class);
        $resultat=$repo->findAll();
        $email1=$request->get('search');
        //var_dump($email1);
        $result1 =$repo->findBy(['EMAIL'=>$email1]);
        //var_dump($resultat);
        // return $this->render('contact/index.html', [
        //     //'controller_name' => 'ContactController',
        //   'Contact' =>  $resultat
        // ]);
        if ($result1!=null) {
            return $this->render('contact/index.html', [
                //'controller_name' => 'ContactController',
                'Contact' =>  $result1,
                'Contact1' =>  ''
                 ]);
            }else{
                return $this->render('contact/index.html', [
                    //'controller_name' => 'ContactController',
                  'Contact'=>'',
                  'Contact1' =>  'Veuillez Saisir Votre Email SVP!'
                ]);
            } 
    }


    /**
     * @Route("/", name="connexion")
     */
    public function connexionUser(Request $request)
    {
        $error = 'LOGIN OU PASSWORD INCORRECT';
        $error1="";     
        $repo =$this->getDoctrine()->getRepository(Utilisateur::class);
        $user = new Utilisateur();
        $email=$request->get('email');
        $password=$request->get('motDepasse');
        if ($email===null || $password===null) {
            return $this->render('contact/connexion.html', [
            'cool' => 0,
        ]);
        }else{
            $log1 = $repo->findOneBy([
                'email' => $email
            ]);
            $password = $repo->findOneBy([
                'password' => $password
            ]);
            if (($log1 && $password)!=null) {
                return $this->redirectToRoute('contact');
                dump($log1,$password);
            }
            if ($log1==null || $password==null) {
                return $this->render('contact/connexion.html', [
            'cool' => 1,
             'error' => $error
            ]);
            }
        }
        //return $this->render('contact/connexion.html'); 
    }
    
    /**
     * @Route("/contact/Ajout", name="Ajout")
     * @Route("/contact/{id}/edit", name="modifier")
     */
    public function ajoutContact(Contact $contact=null ,Request $request ,EntityManagerInterface $manager)
    {
        
                    if($contact==null)
                    {
                       $contact = new Contact();
                       $statut = true;
                     }else{
                       $statut = false;
                     }
       $form = $this->createFormBuilder($contact)
                    ->add('NOM',TextType::class)
                    ->add('PRENOM',TextType::class)
                    ->add('ADRESSE',TextType::class)
                    ->add('TELEPHONE',NumberType::class)
                    ->add('EMAIL',EmailType::class)
                    ->add('DESCRIPTION',TextareaType::class)
                    ->getForm();
                    $form->handleRequest($request);
                    $email=$contact->getEMAIL();
                    $repo = $this->getDoctrine()->getRepository(Utilisateur::class);
                    $info= $repo->findOneBy([
                    'email' => $email
                    ]);
         //var_dump($info);
                   if ($form->isSubmitted() && $form->isValid()) {
                       if ($info!==null) {
                       $manager->persist($contact);
                       $manager->flush();
                      return $this->redirectToRoute('contact');
                       }
                       else
                       {
                         return $this->render('contact/Ajout.html',[
                             'formContact'=> $form->createView(),
                             'error'=> "L'adresse email saisi n'existe pas veuillez saisir votre login",
                             'cool' => 2,
                             'statut' => $statut  
                         ]);   
                       }       
                    }
                    return $this->render('contact/Ajout.html',[
                        'formContact'=> $form->createView(),
                        'cool' => 0 ,
                        'statut' => $statut
                    ]);   
               }

    /**
     * @Route("/contact/Compte", name="Compte")
     */

     public function compte(Request $request ,EntityManagerInterface $manager)
     {
        $succes= 'compte creer avec succes!';
        $error = 'erreur les mots de passes ne sont pas indentique!';
        $pass1=$request->get('pass1');
        $pass2=$request->get('pass2');
        //var_dump($pass1,$pass2);
        if($request->request->count()>0 && ($pass1===$pass2)){
            $user = new Utilisateur();
            $user->setNom($request->request->get('nom'))
                 ->setPrenom($request->request->get('prenom'))
                 ->setEmail($request->request->get('mail'))
                 ->setAdresse($request->request->get('adresse'))
                 ->setphone($request->request->get('telephone'))
                 ->setPassword($request->request->get('pass1'));   

                 $manager->persist($user);
                 $manager->flush();
                 
               return      $this->render('contact/compte.html',[
                    'cool' => 1,
                    'succes' => $succes]);
        }
         if($pass1!=$pass2)
         {
                 return    $this->render('contact/compte.html',[
                    'cool' => 2,
                    'error' => $error
             ]);  
        }
           return $this->render('contact/compte.html',[
             'cool' => 0
          ]);

        //return $this->render('contact/Compte.html');
     }

     /**
     * @Route("/contact/{id}", name="details")
     */
    
     public function detailContact($id)
     {
        $date=date('Y/m/d à H:i:s');
        $repo = $this->getDoctrine()->getRepository(Contact::class);
        $detail = $repo->find($id);
        return $this->render('contact/Detail.html',[
            'DetailCont' => $detail,
            'Date' =>$date
        ]);
     }
    
     /**
     *  @Route("/contact/{id}/supprimer", name="delete")
     */
    public function supprimer($id,Request $request ,EntityManagerInterface $manager)
    {
        $repo = $this->getDoctrine()->getRepository(Contact::class);
        $resultat=$repo->findAll();
        $reponse=$repo->find($id);
        if ($reponse!==null) {
            $manager->remove($reponse);
            $manager->flush();
            return $this->redirectToRoute('contact');
        }
        return $this->render('contact/index.html', [
            //'controller_name' => 'ContactController',
            'Contact' =>  $resultat
        ]);
    }
}
