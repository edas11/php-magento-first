<?php
declare(strict_types = 1);

namespace Edvardas\MyFirstExtension\Controller\Index;
 
class Index extends \Magento\Framework\App\Action\Action
{
  public function __construct(\Magento\Framework\App\Action\Context $context)
  {
    return parent::__construct($context);
  }
 
  public function execute()
  {
    echo 'Hello Magento 2! We will change the world!';
    exit;
  }
}
