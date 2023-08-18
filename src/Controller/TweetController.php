<?php

namespace App\Controller;

use App\Entity\Tweet;
use App\Repository\ActionTypeRepository;
use App\Repository\TweetRelationsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use App\Entity\TweetRelations;
use App\Form\TweetType;
use App\Repository\TweetRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\UX\Turbo\TurboBundle;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Repository\UserRelationsRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/tweets')]
class TweetController extends AbstractController
{
    public function __construct(TweetRepository $tweetRepository, Security $security, EntityManagerInterface $entityManager, TweetRelationsRepository $trRepository, UserRelationsRepository $userRelationsRepository){
      $this->tweetRepository = $tweetRepository;
      $this->security = $security;
      $this->entityManager = $entityManager;
      $this->trRepository = $trRepository;
      $this->userRelationsRepository = $userRelationsRepository;
    }

    // this is used by the turbo stream inside the turbo frame of base url (homecontroller, twig on _show.html).
    #[Route('/newComment', name: 'app_tweet_new', methods: ['GET','POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, Security $security): Response
    {
        // Creating Tweet
        // return $this->redirectToRoute('app_user_index');
        if($request->isMethod('POST')){
          // dd('hola');
          $tweet = new Tweet();
          $description = $request->request->get('description');
          if($description != '') {
            $tweet->setDescription($description);
            $tweet->setLikes(0);
            $tweet->setRetweets(0);
            $tweet->setComments(0);
            $tweet->setCites(0);
            $tweet->setSaved(0);
            $appuser = $this->security->getUser();
            // parent tweet not setted. view
            if($request->request->get('idtweet')){
              $parentTweet = $this->tweetRepository->findOneById($request->request->get('idtweet'));
              $parentTweet->addComment();
              $entityManager->persist($parentTweet);
              $entityManager->flush();
              $tweet->setParent($parentTweet);
            }
            $appuser->addQtweets();
            $tweet->setAuthor($this->security->getUser());
            $tweet->setCreatedAt(new \DateTimeImmutable('now'));
            $this->entityManager->persist($tweet, $appuser);
            $this->entityManager->flush();
            if (TurboBundle::STREAM_FORMAT === $request->getPreferredFormat()) {
              // If the request comes from Turbo, set the content type as text/vnd.turbo-stream.html and only send the HTML to update
              $request->setRequestFormat(TurboBundle::STREAM_FORMAT);
              // return new Response('_tweet_stream.html.twig',200,array('Content-Type'=>'text/vnd.turbo-stream-html'));
              
              return $this->render('_tweetcomment_stream.html.twig', ['tweet' => $tweet]);
            }
          } else {
            // MENSAJE DE ERROR AL FORMULARIO
          }
        } 
    }


    #[Route('/profile/{id}', name: 'app_profile_show_tweet', methods: ['GET','POST'])]
    public function showTweetProfile(Tweet $tweet, Request $request): Response
    {
      if ($request->headers->has('Turbo-Frame')) {
        $auxFather = $tweet;
        $fatherTweet = $tweet->getParent();
        $fatherTweets = new ArrayCollection();
        // dd($a);
        while($auxFather->getParent()){
          $fatherTweets->add($auxFather->getParent());
          $auxFather = $auxFather->getParent();
        }
        $fatherTweets = array_reverse($fatherTweets->toArray());
        $childrens = $tweet->getTweets();
        if($request->isMethod('POST')){
          $newTweet = new Tweet();
          $description = $request->request->get('description');
          $newTweet->setDescription($description);
          $newTweet->setLikes(0);
          $newTweet->setRetweets(0);
          $newTweet->setCites(0);
          $newTweet->setSaved(0);
          $newTweet->setParent($tweet);
          $appuser = $this->security->getUser();
          $newTweet->setAuthor($appuser);
          $newTweet->setCreatedAt(new \DateTimeImmutable('now'));
          $appuser->addQtweets();
          $tweet->addTweet($newTweet);
          // TODOS: SEE HOW TO NEST TWEETS CORRECTLY. MAYBE THE DATABASE HAS A WRONG DESIGN.
          $this->entityManager->persist($newTweet, $appuser);
          $this->entityManager->flush();
        }
        
        return $this->render('tweet/_show_profile.html.twig', [
            'tweet' => $tweet,
            'fatherTweets' => $fatherTweets,
            'relations' => $this->trRepository,
            'childrens' => $childrens
        ]);
      } else {
        $response = new Response();
        $response->setStatusCode(500);
        return $response;
      }
    }

    // this is used by the turbo frame to load the tweet comment. it has also turbo stream.
    #[Route('/{id}', name: 'app_tweet_show', methods: ['GET','POST'])]
    public function show(Tweet $tweet, Request $request): Response
    {
        if ($request->headers->has('Turbo-Frame')) {
          $auxFather = $tweet;
          $fatherTweet = $tweet->getParent();
          $fatherTweets = new ArrayCollection();
          // dd($a);
          while($auxFather->getParent()){
            $fatherTweets->add($auxFather->getParent());
            $auxFather = $auxFather->getParent();
          }
          $fatherTweets = array_reverse($fatherTweets->toArray());
          $childrens = $tweet->getTweets();
          if($request->isMethod('POST')){
            $newTweet = new Tweet();
            $description = $request->request->get('description');
            $newTweet->setDescription($description);
            $newTweet->setLikes(0);
            $newTweet->setRetweets(0);
            $newTweet->setCites(0);
            $newTweet->setSaved(0);
            $newTweet->setParent($tweet);
            $appuser = $this->security->getUser();
            $newTweet->setAuthor($appuser);
            $newTweet->setCreatedAt(new \DateTimeImmutable('now'));
            $appuser->addQtweets();
            $tweet->addTweet($newTweet);
            // TODOS: SEE HOW TO NEST TWEETS CORRECTLY. MAYBE THE DATABASE HAS A WRONG DESIGN.
            $this->entityManager->persist($newTweet, $appuser);
            $this->entityManager->flush();
          }
          
          return $this->render('tweet/_show_home.html.twig', [
              'tweet' => $tweet,
              'fatherTweets' => $fatherTweets,
              'relations' => $this->trRepository,
              'childrens' => $childrens
          ]);
        } else {
          $response = new Response();
          $response->setStatusCode(500);
          return $response;
        }
    }

    // this route is used by the controller tweetActions.
    // #[Route('/{id}/{typeAction}', name: 'app_tweet_action', methods: ['POST'])]
    #[Route('/{id}/{typeAction}', name: 'app_tweet_action', methods: ['GET','POST'])]
    public function likeTweet(Request $request, $id, $typeAction, ActionTypeRepository $actions)
    { 
      $tweet=$this->tweetRepository->findOneById($id);
      switch ($typeAction) {

        case 'like':
          $newTweetRelation = new TweetRelations;
          $newTweetRelation->setUser($this->security->getUser());
          $newTweetRelation->setCreatedAt(new \DateTimeImmutable('now'));
          $newTweetRelation->setUpdatedAt(new \DateTime('now'));
          $newTweetRelation->setTweet($tweet);
          $newTweetRelation->setActionType($actions->findOneById(1));
          $tweet->addLike();
          $this->entityManager->persist($newTweetRelation, $tweet);
          $this->entityManager->flush();
          break;
        case 'dislike':
          $relation = $this->trRepository->findIfExistsLike($tweet, $this->security->getUser());
          $this->entityManager->remove($relation);
          $tweet->subLike();
          $this->entityManager->persist($tweet);
          $this->entityManager->flush();
          break;

        case 'addRtw':
          $newTweetRelation = new TweetRelations;
          $newTweetRelation->setUser($this->security->getUser());
          $newTweetRelation->setCreatedAt(new \DateTimeImmutable('now'));
          $newTweetRelation->setUpdatedAt(new \DateTime('now'));
          $newTweetRelation->setTweet($tweet);
          $newTweetRelation->setActionType($actions->findOneById(2));
          $tweet->addRtw();
          $this->entityManager->persist($newTweetRelation, $tweet);
          $this->entityManager->flush();
          break;
        case 'deleteRtw':
          $relation = $this->trRepository->findIfExistsRt($tweet, $this->security->getUser());
          $this->entityManager->remove($relation);
          $tweet->subRt();
          $this->entityManager->persist($tweet);
          $this->entityManager->flush();
          break;

        // falta hacer todas las funciones de findIfExists relation para cited.
        case 'save':
          $newTweetRelation = new TweetRelations;
          $newTweetRelation->setUser($this->security->getUser());
          $newTweetRelation->setCreatedAt(new \DateTimeImmutable('now'));
          $newTweetRelation->setUpdatedAt(new \DateTime('now'));
          $newTweetRelation->setTweet($tweet);
          $newTweetRelation->setActionType($actions->findOneById(4));
          $tweet->addSave();
          $this->entityManager->persist($newTweetRelation, $tweet);
          $this->entityManager->flush();
          break;
        case 'deleteSave':
          $relation = $this->trRepository->findIfExistsSave($tweet, $this->security->getUser());
          $this->entityManager->remove($relation);
          $tweet->subSave();
          $this->entityManager->persist($tweet);
          $this->entityManager->flush();
          break;
        case 'deleteTweet':
          // $route = $this->request->attributes->get('_route');
          $appuser = $this->security->getUser();
          $tweet = $this->tweetRepository->findOneById($id);


          if($tweet->getParent()){
            $parentTweet = $tweet->getParent();
            $parentTweet->subComment();
            $this->entityManager->persist($parentTweet);
          }

          if($tweet->getTweets()){
            $childTweets = $tweet->getTweets();
            foreach($childTweets as $child){
              $child->setParent(null);
              $this->entityManager->persist($child);
            }
          }

          $this->entityManager->remove($tweet);
          $appuser->subQtweets();
          $this->entityManager->persist($appuser);
          $this->entityManager->flush();
          return $this->redirectToRoute('app_home',
          [ 'tweets'=>$this->tweetRepository->findLatests(),
            'relations' => $this->trRepository
          ]);
          break;
          case 'deleteTweetProfile':
            $appuser = $this->security->getUser();
            $tweet = $this->tweetRepository->findOneById($id);

            if($tweet->getParent()){
              $parentTweet = $tweet->getParent();
              $parentTweet->subComment();
              $this->entityManager->persist($parentTweet);
            }

            if($tweet->getTweets()){
              $childTweets = $tweet->getTweets();
              foreach($childTweets as $child){
                $child->setParent(null);
                $this->entityManager->persist($child);
              }
            }

            $this->entityManager->remove($tweet);
            $appuser->subQtweets();
            $this->entityManager->persist($appuser);
            $this->entityManager->flush();
            $user = $this->security->getUser();
            $tweets = $this->tweetRepository->findLatestsByUser($user->getId());
            return $this->redirectToRoute('app_user_show', ['id' => $user->getId()]);
            break;
        // todo: function on finding a relation based on cite.
        // case 'cite':
        //   $newTweetRelation = new TweetRelations;
        //   $newTweetRelation->setUser($this->security->getUser());
        //   $newTweetRelation->setCreatedAt(new \DateTimeImmutable('now'));
        //   $newTweetRelation->setUpdatedAt(new \DateTime('now'));
        //   $newTweetRelation->setTweet($tweet);
        //   $newTweetRelation->setActionType($actions->findOneById(3));
        //   $tweet->addCite();
        //   $this->entityManager->persist($newTweetRelation, $tweet);
        //   $this->entityManager->flush();
        //   break;
        // case 'deleteCite':
        //   $relation = $this->trRepository->findIfExistsCite($tweet, $this->security->getUser());
        //   $this->entityManager->remove($relation);
        //   $this->entityManager->flush();
        //   $tweet->subCite();
        //   break;
      }

      $response[] = array(
        'MESSAGE' => "doy el ".$typeAction." al tweet id: ".$id
      );
      return new JsonResponse($response);
    }    
}
