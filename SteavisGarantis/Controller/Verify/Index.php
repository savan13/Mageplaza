<?php

namespace Mageplaza\SteavisGarantis\Controller\Verify;

class Index extends \Magento\Framework\App\Action\Action

{

    protected $_pageFactory;
    protected $_context;

    public function __construct(

        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $pageFactory)

    {
        $this->_context = $context;
        $this->_pageFactory = $pageFactory;
        parent::__construct($context);

    }

    public function execute()

    {
        echo "STEAVISGARANTIS";
        exit;

    }

}
