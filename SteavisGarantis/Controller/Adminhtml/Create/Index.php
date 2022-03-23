<?php
namespace Mageplaza\SteavisGarantis\Controller\Adminhtml\Create;
class Index extends \Magento\Backend\App\Action
{
         protected $resultPageFactory = false;      
         public function __construct(
                 \Magento\Backend\App\Action\Context $context,
                 \Magento\Framework\View\Result\PageFactory $resultPageFactory
         ) {
                 parent::__construct($context);
                 $this->resultPageFactory = $resultPageFactory;
         } 
         public function execute()
         {
                 $resultPage = $this->resultPageFactory->create();
                 $resultPage->setActiveMenu('Mageplaza_SteavisGarantis::menu');
                 $resultPage->getConfig()->getTitle()->prepend(__('Create Account'));
				 
                 return $resultPage;
         }
         protected function _isAllowed()
         {
                 return $this->_authorization->isAllowed('Mageplaza_SteavisGarantis::menu');
         }
}