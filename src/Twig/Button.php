<?php
namespace App\Twig;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent()]
class Button {
  public string $buttonType = '';
  public string $message = '';
  
  public function getButtonType(): string {
    return match ($this->buttonType){
      'success' => 'text-green-500 bg-white',
      'info' => 'text-gray-600 bg-gray-100',
    };
  }
}