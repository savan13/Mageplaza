<?php
namespace Mageplaza\SteavisGarantis\Helper;

use Psr\Log\LoggerInterface;
use Magento\Framework\App\Area;
use Magento\Store\Model\ScopeInterface;
use Magento\Framework\App\Helper\Context;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\Exception\MailException;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Mail\Template\TransportBuilder;
use Magento\Framework\Translate\Inline\StateInterface;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    const EMAIL_TEMPLATE = 'email_section/sendmail/email_template';

    const EMAIL_SERVICE_ENABLE = 'email_section/sendmail/enabled';

    /**
     * @var StateInterface
     */
    private $inlineTranslation;

    /**
     * @var TransportBuilder
     */
    private $transportBuilder;

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * Data constructor.
     * @param Context $context
     * @param StoreManagerInterface $storeManager
     * @param TransportBuilder $transportBuilder
     * @param StateInterface $inlineTranslation
     * @param LoggerInterface $logger
     */
	 
	 
	protected $context;
    public function __construct(
        Context $context,
        StoreManagerInterface $storeManager,
        TransportBuilder $transportBuilder,
        StateInterface $inlineTranslation,
        LoggerInterface $logger
    )
    {
        $this->storeManager = $storeManager;
        $this->transportBuilder = $transportBuilder;
        $this->inlineTranslation = $inlineTranslation;
        $this->logger = $logger;
		$this->context = $context;
		$this->scopeConfig = $context->getScopeConfig();
        parent::__construct($context);
    }
	public function FrenchApi(){
		return $this->scopeConfig->getValue('sagwidget/jswidget/api', ScopeInterface::SCOPE_STORE);
	}
	public function EnglishApi(){
		return $this->scopeConfig->getValue('sagwidget/jswidget/api_english', ScopeInterface::SCOPE_STORE);
	}
	public function GermanApi(){
		return $this->scopeConfig->getValue('sagwidget/jswidget/api_german', ScopeInterface::SCOPE_STORE);
	}
	public function SpanishApi(){
		return $this->scopeConfig->getValue('sagwidget/jswidget/api_spanish', ScopeInterface::SCOPE_STORE);
	}
	public function ItalianApi(){
		return $this->scopeConfig->getValue('sagwidget/jswidget/api_italian', ScopeInterface::SCOPE_STORE);
	}
	public function NetherlandApi(){
		return $this->scopeConfig->getValue('sagwidget/jswidget/api_dutch', ScopeInterface::SCOPE_STORE);
	}

    /*Get Customer Order Status*/
    public function getCustomerOrderStatus($storeCode) {
        $list = $this->scopeConfig->getValue("sagwidget/jswidget/order_status_planned_to_ship",
                \Magento\Store\Model\ScopeInterface::SCOPE_STORE,$storeCode);
        return $list !== null ? explode(',', $list) : [];
		//$storeLang=$_POST['lang'];  // en , fr,
    }
	
	public function isEnable(){
		return $this->scopeConfig->getValue('sagwidget/jswidget/enablejs', ScopeInterface::SCOPE_STORE);
	}
	public function isEnableiframe(){
		return $this->scopeConfig->getValue('sagwidget/jswidget/enableiframe', ScopeInterface::SCOPE_STORE);
	}
	public function isEnablefooterlink(){
		return $this->scopeConfig->getValue('sagwidget/jswidget/enablefooterlink', ScopeInterface::SCOPE_STORE);
	}
	public function isEnablestarrating(){
		return $this->scopeConfig->getValue('sagwidget/jswidget/starcategories', ScopeInterface::SCOPE_STORE);
	}
	 public function getLabel()
    {
		$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
		$storeManager = $objectManager->get('\Magento\Store\Model\StoreManagerInterface');
		$storeName = $storeManager->getStore()->getName();
		//print_r ($storeName); 
		//echo $storeName; 
		
        //Your logic here

		if($storeName =='France'){ 
			$title = "Avis clients";
		}
		else if($storeName =='English'){ 
			$title = "Customer reviews";
		}
		else if($storeName =='German'){ 
			$title = "Kunden-Bewertungen";
		}
		else if($storeName =='Spanish'){ 
			$title = "Opiniones de los clientes";
		}
		else if($storeName =='Italian'){ 
			$title = "Recensioni dei clienti";
		}
		else if($storeName =='Netherland'){ 
			$title = "Beoordelingen van klanten";
		} 
        

        //$title = "Review Tab";
        return $title;
    } 
	public function isGetOption()
    {
        return $this->scopeConfig->getValue('sagwidget/jswidget/enablereview', ScopeInterface::SCOPE_STORE);
		//$sliderConfig = \Magento\Framework\App\ObjectManager::getInstance()->get('Namespace\Slideshow\Model\Config\Source\Slideshow');

		//\Magento\Framework\App\Config\ScopeConfigInterface::getValue
    }
	// public function getStoreValue(){
		// return $this->scopeConfig->getValue('general/store_information/name',\Magento\Store\Model\ScopeInterface::SCOPE_STORE);
	// }
    /**
     * Send Mail
     *
     * @return $this
     *
     * @throws LocalizedException
     * @throws MailException
     */
    public function sendMail(){
        $email = 'sweta@virtualheight.com'; //set receiver mail

        $this->inlineTranslation->suspend();
        $storeId = $this->getStoreId();

        /* email template */
        $template = $this->scopeConfig->getValue(
            self::EMAIL_TEMPLATE,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );

        $vars = [
            'message_1' => 'CUSTOM MESSAGE STR 1',
            'message_2' => 'custom message str 2',
            'store' => $this->getStore()
        ];

        // set from email
        $sender = $this->scopeConfig->getValue(
            'email_section/sendmail/sender',
            ScopeInterface::SCOPE_STORE,
            $this->getStoreId()
        );

        $transport = $this->transportBuilder->setTemplateIdentifier(
            $template
        )->setTemplateOptions(
            [
                'area' => Area::AREA_FRONTEND,
                'store' => $this->getStoreId()
            ]
        )->setTemplateVars(
            $vars
        )->setFromByScope(
            $sender
        )->addTo(
            $email
        )->addBcc(
           ['receiver1@example.com', 'receiver2@example.com']
        )->getTransport();

        try {
            $transport->sendMessage();
        } catch (\Exception $exception) {
            $this->logger->critical($exception->getMessage());
        }
        $this->inlineTranslation->resume();

        return $this;
    }

    /*
     * get Current store id
     */
    public function getStoreId(){
        return $this->storeManager->getStore()->getId();
    }

    /*
     * get Current store Info
     */
    public function getStore(){
        return $this->storeManager->getStore();
    }
	
	public function getConfig($config_path)
    {
        return $this->scopeConfig->getValue('sagwidget/jswidget/enablereview',\Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }
	
	
}