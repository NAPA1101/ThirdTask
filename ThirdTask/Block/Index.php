<?php

namespace Perspective\ThirdTask\Block;

use Magento\Framework\View\Element\Template\Context;
use Magento\Framework\Registry;
use Magento\CatalogRule\Model\Rule;
use Perspective\ThirdTask\Helper\Data;

class Index extends \Magento\Framework\View\Element\Template
{
    /**
     * @var Registry
     */
    protected $_registry;

    /**
     * @var Rule
     */
    protected $_rule;

    /**
     * @var Data
     */
    protected $_helper;

    /**
     * @param Context $context
     * @param Registry $registry
     * @param Rule $rule
     * @param Data $helper
     * @param array $data
     */
    public function __construct(
        Context  $context,
        Registry $registry,
        Rule $rule,
        Data $helper,
        array    $data = [])
    {
        parent::__construct($context, $data);
        $this->_registry = $registry;
        $this->_rule = $rule;
        $this->_helper = $helper;

    }

    /**
     * @return mixed|null
     */
    public function getCurrentProduct()
    {
        return $this->_registry->registry('current_product');
    }

    /**
     * @return string|void
     */
    public function getSpecialDate()
    {
        if($this->_helper->getGeneralConfig('enable')) {
            $endSpecialDate = $this->getCurrentProduct()->getSpecialToDate();
            if ($endSpecialDate) {
                $data = date_create($endSpecialDate);
                return date_format($data, 'Y/m/d');
            }
        }
    }

    /**
     * @param $ruleId
     * @return string|void
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getRuleDate($ruleId)
    {
        if($this->_helper->getGeneralConfig('enable')) {
            $rules = $this->_rule->getResourceCollection()->addFieldToFilter('rule_id', $ruleId);
            $productId = $this->getCurrentProduct()->getId();
            if ($rules) {
                foreach ($rules as $rule) {
                    if (isset($rule->getMatchingProductIds()[$productId][1])) {
                        $data = date_create($rule->getToDate());
                        return date_format($data, 'Y/m/d');
                    }
                }
            }
        }
    }

    /**
     * @return mixed
     */
    public function getSpecialEnable() {
        return $this->_helper->getGeneralConfig('specialEnable');
    }

    /**
     * @return array
     */
    public function getRuleEnable() {
        $mass[] = $this->_helper->getGeneralConfig('rulePensionersEnable');
        $mass[] = $this->_helper->getGeneralConfig('ruleTestEnable');
        return $mass;
    }

    /**
     * @return mixed
     */
    public function getComparison() {
        return $this->_helper->getGeneralConfig('min');
    }
}
