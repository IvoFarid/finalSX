<?php
namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\TweetRepository;
use App\Repository\TweetRelationsRepository;
use App\Repository\UserRelationsRepository;
use Symfony\Component\Security\Core\Security;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\UserRepository;
use Symfony\UX\Turbo\TurboBundle;
use App\Entity\Tweet;

#[Route('/')]
class HomeController extends AbstractController {

  public function __construct(TweetRepository $tweetRepository, Security $security, EntityManagerInterface $entityManager, TweetRelationsRepository $trRepository, UserRelationsRepository $userRelations){
    $this->tweetRepository = $tweetRepository;
    $this->security = $security;
    $this->entityManager = $entityManager;
    $this->userRelations = $userRelations;
    $this->trRepository = $trRepository;
  }

  #[Route('', name: 'app_home', methods: ['GET', 'POST'])]
  public function index(Request $request, TweetRepository $tweetsRepository): Response {
    $userRelations = $this->trRepository->findByUser($this->security->getUser());
    
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
        $tweet->setAuthor($appuser);
        $tweet->setCreatedAt(new \DateTimeImmutable('now'));
        $appuser->addQtweets();
        $this->entityManager->persist($tweet, $appuser);
        $this->entityManager->flush();
        
        if (TurboBundle::STREAM_FORMAT === $request->getPreferredFormat()) {
          // If the request comes from Turbo, set the content type as text/vnd.turbo-stream.html and only send the HTML to update
          $request->setRequestFormat(TurboBundle::STREAM_FORMAT);
          // return new Response('_tweet_stream.html.twig',200,array('Content-Type'=>'text/vnd.turbo-stream-html'));
          return $this->render('_tweethome_stream.html.twig', [
          'tweet' => $tweet,
          'relations' => $this->trRepository
        ]);
        }
      } else {
        // MENSAJE DE ERROR
      }
    } 
    
    return $this->render('tweet/index.html.twig', [
        'tweets' => $this->tweetRepository->findLatests(),
        'relations' => $this->trRepository
    ]);
  }

  // TODO
  #[Route('/followed', name: 'app_followed', methods: ['GET'])]
  public function followed(): Response {
    $appuser = $this->security->getUser();
    $followedUsers = $this->userRelations->findFollowedUsers($appuser);
    $allTweets = [];

    foreach ($followedUsers as $user) {
      $tweets = $user->getTweets();
      foreach($tweets as $tweet){
        $allTweets[] = $tweet;
      }
    }

    function compareTweetsByCreatedAt($tweetA, $tweetB) {
      return strtotime($tweetA['createdAt']) - strtotime($tweetB['createdAt']);
    }

    usort($allTweets, function($a, $b)
    {
        return $a->getCreatedAt() < $b->getCreatedAt();
    });
    
    return $this->render('tweet/followed.html.twig', [
      'tweets' => $allTweets,
      'relations' => $this->trRepository
    ]);
  }
}