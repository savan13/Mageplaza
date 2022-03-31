<?php
namespace Mageplaza\SteavisGarantis\Controller\Sales;

use Magento\Framework\App\CsrfAwareActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\Request\InvalidRequestException;


class Index extends \Magento\Framework\App\Action\Action implements CsrfAwareActionInterface
{
	protected $_orderCollectionFactory;
	protected $helperData;
	protected $_pageFactory;
	public $ApiEndPoint="https://www.societe-des-avis-garantis.fr/wp-content/plugins/ag-core/api/";
	
	
	public function createCsrfValidationException(RequestInterface $request): ? InvalidRequestException
	{
		return null;
	}
    public function validateForCsrf(RequestInterface $request): ?bool
	{
		return true;
	}
	/**
     * @var LoggerInterface
     */
    

	public function __construct(
		\Magento\Framework\App\Action\Context $context,
		\Mageplaza\SteavisGarantis\Helper\Data $helperData,
		\Magento\Sales\Model\ResourceModel\Order\CollectionFactory $orderCollectionFactory,		
		\Magento\Framework\View\Result\PageFactory $pageFactory)
		
	{
		$this->_pageFactory = $pageFactory;
		$this->_orderCollectionFactory = $orderCollectionFactory;
		
		$this->helperData = $helperData;
		return parent::__construct($context);
	}

	public function execute()
	{

		
		$token="";
	
//	$storeLang=$_POST['lang'];  // en , fr,
		
		
		//echo $storeManager->getStore()->getStoreName();

		
		

		// We get the date between which we want to get orders from 
	//	$fromDate = (isset ($_POST["fromDate"])? $_POST["fromDate"]:''); 
//		$toDate = (isset ($_POST["toDate"])? $_POST["toDate"]:'');
		// Let's get the token
		$token = (isset ($_POST["token"])? $_POST["token"]:'');
		// the message
	
		echo $token ;
	/*	$alldata=json_decode($_POST);
		
		$url='https://workdemo.me/wp_demp/mydb.php?token='.$alldata;
		$ch = curl_init ($url);
		curl_setopt ($ch, CURLOPT_FOLLOWLOCATION, 1); 
		curl_setopt ($ch, CURLOPT_HEADER, 0); 
		curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1); 
		curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0); 
		curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, 0); 
		curl_exec ($ch);
		
		*/
		// We check token validity 
		$checkAnswer = $this->tokenCheck($token);
		
		// If we don't have the right answer from SAG
		if (strpos ($checkAnswer, "ValidSagData") === false) {
			exit;
		}
	
		$orderList=$this->getOrders();
		$posted = $this->postData($orderList, "bulkOrderInfos.php", $token);		
		exit;
		
	}


	
	function getOrders(){
	
			//$storeId = '2';
			$fromDate = (isset ($_POST["fromDate"])? $_POST["fromDate"]:'2021-10-01'); 
			$toDate = (isset ($_POST["toDate"])? $_POST["toDate"]:'2022-10-01');
			
			$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
			$storeManager = $objectManager->get('\Magento\Store\Model\StoreManagerInterface');
			$storeId = $storeManager->getStore()->getId();
			$StoreId = $storeManager->getStore()->getStoreId();
			$storeName = $storeManager->getStore()->getName();
			$storeCode = $storeManager->getStore()->getCode();
			$storeLang= (isset ($_POST["lang"])? $_POST["lang"]:'fr');//$_POST['lang'];  // en , fr,		
		
			
			$objectManager = \Magento\Framework\App\ObjectManager::getInstance(); 
			$storeManager = $objectManager->get('\Magento\Store\Model\StoreManagerInterface');
			$storeCode="";
		

			$scopeConfig = $objectManager->get('Magento\Framework\App\Config\ScopeConfigInterface');
				$stores = ($storeManager->getStores(true));
		
				foreach($stores as $store){
	
				//$store = $objectManager->get('Magento\Framework\Locale\Resolver'); 2
				
			
				if($store->getCode()==$storeLang)
					$StoreId=$store->getId();

				}
	
			$values = $this->helperData->getCustomerOrderStatus($storeLang);
			$List = implode(', ', $values);
			
			$collection = $this->_orderCollectionFactory->create()
				->addAttributeToSelect('*')
		 		->addFieldToFilter('created_at',
               		['gteq' => $fromDate]
            	)
         		->addFieldToFilter('created_at',
                	['lteq' => $toDate]
            	)
				->addFieldToFilter('status',
                	['in' => $values]
            	)
				->addFieldToFilter('store_id',
                	['eq' => $StoreId]
            	);

				
			foreach($collection as $order ){
				$products=array();
                /** @var Mage_Sales_Model_Order_Item $_item */
				
				 $items = [];
					foreach ($order->getItemsCollection() as $_item) {
						//echo $item->getId()."<br>";
						if (!$_item->isDeleted() && !$_item->getParentItemId()) {
							$items[] = $_item;
							
							$products[] = array(
							"id"   => $_item->getProductId(),
							"name" => $_item->getName(),
							"sku"  => $_item->getProduct()->getSku(),
						);
						}
					}
			 $datasToExport[] = array(
                    'id_site'    => $this->getStoreId(),
                    'id_order'   => $order->getId(),
                    'reference'  => $order->getIncrementId(),
                    'order_date' => $order->getCreatedAt(),
                    'firstname'  => $order->getCustomerFirstname(),
                    'lastname'   => $order->getCustomerLastname(),
                    'email'      => $order->getCustomerEmail(),
                    'products'   => $products
                );
				
			}
			return  $datasToExport;
	}
	// Verify a token
	function tokenCheck($token) {
		$apiKey = urlencode($this->yourFunctionToGetApiKeyFromConfiguration());
		$url = $this->ApiEndPoint. "checkToken.php?token=". urlencode($token). "&apiKey=". $apiKey; 
		$ch = curl_init ($url);
		curl_setopt ($ch, CURLOPT_FOLLOWLOCATION, 1); 
		curl_setopt ($ch, CURLOPT_HEADER, 0); 
		curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1); 
		curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0); 
		curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, 0); 
		return curl_exec ($ch);
	}
	function yourFunctionToGetApiKeyFromConfiguration(){
		$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
		$storeManager = $objectManager->get('\Magento\Store\Model\StoreManagerInterface');
		$StoreId = $storeManager->getStore()->getStoreId();
		$storeName = $storeManager->getStore()->getName();
		$storeCode = $storeManager->getStore()->getCode();
		
		//return "7066/fr/020c0bc2164f049a7240e3724e906dcc4a6961cf72982a7ef0311bb8fd2256c8";
		 $storeCode= (isset ($_POST["lang"])? $_POST["lang"]:'fr');
		if($storeCode == 'fr'){
			$apikey = $this->helperData->FrenchApi(); 
			
		}else if ($storeCode == 'en') {
			$apikey = $this->helperData->EnglishApi(); 
			
		}else if ($storeCode == 'de') {
			$apikey = $this->helperData->GermanApi();
		
		}else if ($storeCode == 'it') {
			$apikey = $this->helperData->ItalianApi(); 
			
		}else if ($storeCode == 'nl') {
			$apikey = $this->helperData->NetherlandApi(); 
			
		}else if ($storeCode == 'es') {
			$apikey = $this->helperData->SpanishApi();
		
		} 
		return $apikey;
	}
	
	// Safe Function to post datas to SAG function 
	function postData($data, $dest, $token) {
		$apiKey = urlencode($this->yourFunctionToGetApiKeyFromConfiguration()); 
		$url =$this->ApiEndPoint. $dest. "?token=$token&apiKey=".$apiKey; 
		$dataString = base64_encode(json_encode($data));
		$ch = curl_init ($url); curl_setopt ($ch,CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, array ("data" => $dataString)); curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_HEADER, 0); 
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0); 
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0); 
		return curl_exec($ch);
		}
		
		function getStoreId(){
			$ApiKey= $this->yourFunctionToGetApiKeyFromConfiguration();
			list($shopId) = explode('/', $ApiKey);
			return $shopId;	
		}	
}