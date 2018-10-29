<?php
declare(strict_types = 1);

namespace Edvardas\MyFirstExtension\Model\Attribute\Source;

class Featured extends \Magento\Eav\Model\Entity\Attribute\Source\AbstractSource
{

    public function getAllOptions()
    {
        if (!$this->_options) {
            $this->_options = [
                ['label' => __('Yes'), 'value' => '1'],
                ['label' => __('No'), 'value' => '0'],
            ];
        }
        return $this->_options;
    }
}