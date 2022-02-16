<?php

namespace Perspective\ThirdTask\Block;

use Magento\Framework\View\Element\Template\Context;
use Magento\Framework\Registry;

class Index extends \Magento\Framework\View\Element\Template
{
    protected $_registry;

    public function __construct(
        Context  $context,
        Registry $registry,
        array    $data = [])
    {
        parent::__construct($context, $data);
        $this->_registry = $registry;

    }

    public function getCurrentProduct()
    {
        return $this->_registry->registry('current_product');
    }

    public function getSpecialDate()
    {
        $endSpecialDate = $this->getCurrentProduct()->getSpecialToDate();
            if ($endSpecialDate) {
                $date = date_create($endSpecialDate);
                return $date->Format('Y-m-d');
            }
    }
}
