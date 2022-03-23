<?php
namespace Mageplaza\SteavisGarantis\Block;

class Module extends \Magento\Framework\View\Element\Template
{
    protected $_storeManager;    

    /**
     * Constructor
     *
     * @param \Magento\Framework\View\Element\Template\Context  $context
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\Registry $registry,
        array $data = []
    ) {
        $this->_storeManager = $storeManager;
        $this->_registry = $registry;
        parent::__construct($context, $data);
    }
    public function getStoreId()
    {
        return $this->_storeManager->getStore()->getId();
    }
    public function getCurrentCategory()
    {
        return $this->_registry->registry('current_category');
    }
	
}