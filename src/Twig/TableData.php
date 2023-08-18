<?php
namespace App\Twig;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;
use App\Repository\UserRepository;
use App\Repository\TweetRepository;

#[AsTwigComponent()]
class TableData {
  // array received
  public array $values;
  // title for table header
  public string $title;
  // title to set table headers
  public array $tableHeaders;
  // redirect actions labels to table row
  public array $redirectActionsLabel;
  // redirect actions paths to table row
  public array $redirectActionsPath;
  // submit actions labels to table row
  public array $submitActionsLabel;
  // submit actions paths to table row
  public array $submitActionsPath;

  public function setClasses(): string {
    return 'bg-red-500 p-4';
  }
}
