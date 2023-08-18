<?php
namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use App\Repository\TweetRelationsRepository;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\TweetRepository;
use App\Repository\UserRelationsRepository;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Repository\UserRepository;
use App\Entity\Tweet;
use App\Entity\UserRelations;

#[Route('/users')]
class UserController extends AbstractController {

  public function __construct(UserRepository $userRepository, Security $security, EntityManagerInterface $entityManager, TweetRepository $tweetRepository, TweetRelationsRepository $trRepository, UserRelationsRepository $userRelationsRepository){
    $this->entityManager = $entityManager;
    $this->userRepository = $userRepository;
    $this->userRelationsRepository = $userRelationsRepository;
    $this->tweetRepository = $tweetRepository;
    $this->security = $security;
    $this->trRepository = $trRepository;
  }

  #[Route('', name: 'app_user_index', methods: ['GET'])]
  public function getUsers(UserRepository $userRepository): Response {
    $users = $userRepository->findAll();
    return $this->render('users/index.html.twig', [
      'users' => $users
    ]);
  }

  #[Route('/{id}/edit', name: 'app_user_edit', methods: ['GET','POST'])]
  public function editUser(Request $request, $id) {
    $user = $this->userRepository->findOneById($id);
    if($request->isMethod('POST')){
      $newName= $request->request->get('username');
      if ($newName != '') {
        $user->setUsername($newName);
      }
      $imageFile = $request->files->get('image');
      if ($imageFile){
        $newFileName = uniqid() . '.' . $imageFile->guessExtension();
        if($user->getImagePath() !== null){
          $oldFilename = $user->getImagePath();
          if
          (file_exists($this->getParameter('kernel.project_dir') . $user->getImagePath()))
            {
              $this->GetParameter('kernel.project_dir') . $user->getImagePath();
            }
            $oldFilename = str_replace("/",'\\',$oldFilename);
            // dd($this->GetParameter('kernel.project_dir') . $oldFilename);
            // $filesystem = new Filesystem();
            // $filesystem->remove($root . $oldFilename);
            if
              (file_exists($this->getParameter('kernel.project_dir')  . '\public\\' . $oldFilename))
                {
                  unlink($this->GetParameter('kernel.project_dir') . '\public\\' . $oldFilename);
                }
      }
      try {
        $imageFile->move(
            $this->getParameter('kernel.project_dir') . '/public/uploads/',
            $newFileName
        );
        $user->setPhoto('/uploads/' . $newFileName);
        // $root = str_replace("\\",'/',$this->GetParameter('kernel.project_dir'));
        } catch (FileException $e){
            return new Response($e->getMessage());
        }

      $this->entityManager->persist($user);
      $this->entityManager->flush();
    }
  }
    $tweets = $this->tweetRepository->findLatestsByUser($user->getId());
    return $this->redirectToRoute('app_user_show', ['id'=> $id]);
  }

  #[Route('/{id}', name: 'app_user_show', methods: ['GET', 'POST'])]
  public function index(Request $request, $id, UserRepository $userRepository): Response {
    // TODO: filter the data based on if it's the user profile or another user.
    $user = $this->userRepository->findOneById($id);
    if($request->isMethod('POST')){
      dd($user);
    }
    $tweets = $this->tweetRepository->findLatestsByUser($user->getId());

    return $this->render('/users/show.html.twig', [
      "user" => $user,
      "tweets" => $tweets,
      "trelations" => $this->trRepository,
      "urelations" => $this->userRelationsRepository
    ] );
  }

  #[Route('/{id}/delete', name: 'app_user_delete', methods: ['POST'])]
  public function deleteUser(Request $request, $id) {
    $user = $this->userRepository->findOneById($id);
    $this->entityManager->remove($user);
    $this->entityManager->flush();

    return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
  }

  #[Route('/{id}/following', name: 'app_user_following', methods: ['GET'])]
  public function getFollowing(Request $request, UserRepository $userRepository, $id): Response {
    if ($request->headers->has('Turbo-Frame')) {
      // This is a Turbo Frame request, process accordingly
      // Return the desired HTML content for the Turbo Frame
      $user = $this->userRepository->findOneById($id);
      $users = $this->userRelationsRepository->findFollowedUsers($user);
      return $this->render('users/_profile_follows.html.twig', [
        'users' => $users,
        'id' => $id
      ]);
  } else {
    // This is a direct user access, handle accordingly
    // Redirect or return an error response
    $response = new Response();
    $response->setStatusCode(500);
    return $response;
  }
   
  }

  #[Route('/{id}/followers', name: 'app_user_followers', methods: ['GET'])]
  public function getFollowers(Request $request, UserRepository $userRepository, $id): Response {
    if ($request->headers->has('Turbo-Frame')) {
      $user = $this->userRepository->findOneById($id);
      $users = $this->userRelationsRepository->findFollowingUsers($user);
      return $this->render('users/_profile_follows.html.twig', [
        'users' => $users,
        'id' => $id
      ]);
    } else {
      // This is a direct user access, handle accordingly
      // Redirect or return an error response
      $response = new Response();
      $response->setStatusCode(500);
      return $response;
    }
  }

  #[Route('/{id}/liked', name: 'app_user_liked', methods: ['GET'])]
  public function getLiked(Request $request, UserRepository $userRepository, $id): Response {
    if ($request->headers->has('Turbo-Frame')) {
      $user = $this->userRepository->findOneById($id);
      $tweets = $this->trRepository->findLikedByUser($user);

      return $this->render('users/_profile_liked.html.twig', [
        'tweets' => $tweets,
        'id' => $id
      ]);
    } else {
      // This is a direct user access, handle accordingly
      // Redirect or return an error response
      $response = new Response();
      $response->setStatusCode(500);
      return $response;
    }
  }

  #[Route('action/{idprofile}/{typeAction}', name: 'app_user_action', methods: ['GET','POST'])]
  public function setFollow(Request $request, $idprofile, $typeAction, UserRelationsRepository $userRelationsRepository)
  { 
    // CLIENT SIDE ALREADY WORKING. SET THE VALUES HERE. CREATE THE USERRELATION. USE THE IDS. UPDATE FOLLOWERS/FOLLOWING IN EACH USER. FLUSH.
    $appuser = $this->security->getUser();
    $profileUser = $this->userRepository->findOneById($idprofile);
    // $tweet=$this->tweetRepository->findOneById($id);
    switch ($typeAction) {
      case 'follow':
        $newRelation = new UserRelations();
        $newRelation->setFollower($appuser);
        $newRelation->setFollowing($profileUser);
        $newRelation->setCreatedAt(new \DateTimeImmutable('now'));
        // dd($newRelation);
        $profileUser->addFollower();
        $appuser->addFollowing();
        //VIEW PERSISTING ENTITIES
        $this->entityManager->persist($newRelation, $appuser, $profileUser);
        $this->entityManager->flush();
        break;
      case 'unfollow':
        $relation = $userRelationsRepository->findIfExistsRelation($appuser, $profileUser);
        $appuser->subFollowing();
        $profileUser->subFollower();
        $this->entityManager->remove($relation);
        $this->entityManager->persist($appuser, $profileUser);
        $this->entityManager->flush();
        break;
    }
    // DO NOT KNOW IF I NEEDED IT
    $response[] = array(
      'MESSAGE' => "doy el ".$typeAction." al tweet id: ".$idprofile
    );
    return new JsonResponse($response);
  }
}