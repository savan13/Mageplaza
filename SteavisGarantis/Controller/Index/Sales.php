<?php
namespace Mageplaza\SteavisGarantis\Controller\Index;

class Sales extends \Magento\Framework\App\Action\Action
{
	protected $helperData;
	protected $_pageFactory;
	public $ApiEndPoint="https://www.societe-des-avis-garantis.fr/wp-content/plugins/ag-core/api/";
	
	public function __construct(
		\Magento\Framework\App\Action\Context $context,
		\Mageplaza\SteavisGarantis\Helper\Data $helperData,
		\Magento\Framework\View\Result\PageFactory $pageFactory)
	{
		$this->helperData = $helperData;
		$this->_pageFactory = $pageFactory;
		return parent::__construct($context);
	}

	public function execute()
	{
			echo "Hello Magento <br>";
		
		// We get the date between which we want to get orders from 
		$fromDate = (isset ($_POST["fromDate"])? $_POST["fromDate"]:''); 
		$toDate = (isset ($_POST["toDate"])? $_POST["toDate"]:'');
		// Let's get the token
		$token = (isset ($_POST["token"])? $_POST["token"]:'');
		// We check token validity 

		echo 'Token:' . $token . '<br>';
		$checkAnswer = $this->tokenCheck($token);
		var_dump($checkAnswer); 
		exit;
	}
	// Verify a token
	function tokenCheck($token) {
		$apiKey = urlencode($this->yourFunctionToGetApiKeyFromConfiguration());
		$url = $this->ApiEndPoint. "checkToken.php? token =". $token. "& apiKey =". $apiKey; 
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
}
