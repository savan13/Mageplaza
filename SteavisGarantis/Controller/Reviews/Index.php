<?php
namespace Mageplaza\SteavisGarantis\Controller\Reviews;
use Magento\Framework\App\CsrfAwareActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\Request\InvalidRequestException;
use Mageplaza\SteavisGarantis\Model\DataExampleFactory;
use Mageplaza\SteavisGarantis\Model\DataExample;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\ResourceConnection;



class Index extends \Magento\Framework\App\Action\Action implements CsrfAwareActionInterface
{
	protected $_orderCollectionFactory;
	protected $helperData;
	protected $_pageFactory;
	protected $_dataExample;
	protected $_DataExampleFactory;
    protected $resultRedirect;
	private $resourceConnection;
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
		\Magento\Framework\View\Result\PageFactory $pageFactory,
		\Mageplaza\SteavisGarantis\Model\DataExampleFactory $dataExample,
		\Magento\Framework\Controller\ResultFactory $result)
	{
		$this->_pageFactory= $pageFactory;
		$this->_dataExample = $dataExample;
        $this->resultRedirect = $result;
		$this->_orderCollectionFactory= $orderCollectionFactory;
		$this->helperData = $helperData;
		return parent::__construct($context);
	}

	public function execute(){
		$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
		$storeManager = $objectManager->get('\Magento\Store\Model\StoreManagerInterface');
		echo "Store ID:"; 
		echo $storeManager->getStore()->getStoreId();
		echo "<br/>"; 
		$storeCode = $storeManager->getStore()->getCode();
		echo "Store Code:"; 
		echo $storeCode; 

		$apiKey= $this->yourFunctionToGetApiKeyFromConfiguration();
		//echo '<br>';
		$headers = explode('/', $apiKey);
		$api = $headers[0];
		//print_r ($headers);
		//echo '<br>';
		// Define SAG url
		$sagUrl= "https://www.societe-des-avis-garantis.fr/";

		// Get vars to send to SAG API
		$productID= isset($_GET['productID'])? $_GET ['productID']: false; 
		$idSAG= isset
		($_GET['idSAG'])? $_GET['idSAG']: false; 
		$minDate= isset($_GET['minDate'])? $_GET['minDate']: false; 
		$maxDate= isset($_GET['maxDate'])? $_GET ['maxDate']: false; 
		$maxResults= isset($_GET['maxR'])? $_GET ['maxR']: false; 
		$token= isset($_GET['token'])? $_GET ['token']: false; 
		$from= isset($_GET['from'])? $_GET ['from']: false;
		$update= isset($_GET['update'])? $_GET['update']: false;
		// Build URL(as we pass datas through GET method) 
		$productID= $productID? '&productID='. $productID: ''; 
		$idSAG= $idSAG? '&idSAG='. $idSAG: '';
		$from= $from? '&from='. $from: '';
		$minDate= $minDate? '&minDate='. $minDate: ''; 
		$maxDate= $maxDate? '&maxDate='. $maxDate: ''; 
		$maxResults= $maxResults?'&maxR='. $maxResults: ''; 
		$token= $token? '&token='. $token: ''; 
		// token to check if SAG asked product reviews update
		// Filter on product ID 
		// Filter on SAG unique review ID
		// If from= 1, we only get reviews starting from idSAG
		// Filter on review date 
		// Filter on review date 
		// Max results to display
		
		
		$url='https://workdemo.me/wp_demp/mydb.php?token='.$token;
		$ch = curl_init ($url);
		curl_setopt ($ch, CURLOPT_FOLLOWLOCATION, 1); 
		curl_setopt ($ch, CURLOPT_HEADER, 0); 
		curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1); 
		curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0); 
		curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, 0); 
		curl_exec ($ch);
		echo "<pre>";
		$content='[{"idSAG":"201370","idProduct":"1","review_rating":"5","review_text":"Where am I","reviewer_name":"John", "lastname":"mi","date_time":"2017-11-03 13:00:46","review_status":"0","answer_text":"","answer_date_time": null,"lang":"fr"}]'; 
		$this->storeReview($content);
		 
		 
		//die();
		  
		 
		echo "<br/>";
		echo 'Token: ' . $token;
		echo "<br/>";
		echo $productID;
		echo $apiUrl=(string) $sagUrl. "wp-content/plugins/ag-core/api/reviews.php?apiPost=1". $productID. $idSAG. $from. $minDate. $maxDate. $maxResults. $token;
		// Send reviews request to SAG API 
		$ch= curl_init();
		$timeout= 10; 
		// Timeout in seconds 
		//curl_setopt($ch, CURLOPT_URL, "http://www.example.com/");
		curl_setopt($ch, CURLOPT_URL, $apiUrl); 
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_POST, 1); 
		curl_setopt($ch, CURLOPT_POSTFIELDS,"apiKey=". urlencode($apiKey)); 
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0); 
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0); 
		curl_setopt($ch,CURLOPT_CONNECTTIMEOUT, $timeout); 
		echo $reviews= curl_exec($ch);
		curl_close($ch);
		$this->storeReview($reviews);
		exit;
	}
	
	function storeReview($content){
		 
		$jsonObj=json_decode($content);
		$resultRedirect = $this->resultRedirect->create(ResultFactory::TYPE_REDIRECT);
        $resultRedirect->setUrl($this->_redirect->getRefererUrl());
		$model = $this->_dataExample->create();
		
		foreach($jsonObj as $obj){
			print_r($obj);
			
			// do code to store data in table
			// sample data {"idSAG":"201369","idProduct":"1","review_rating":"5","review_text":"Where am I","reviewer_name":"John", "lastname":"mi","date_time":"2017-11-03 13:00:46","review_status":"0","answer_text":"","answer_date_time": null,"lang":"fr"}			

			$customerId = $obj->idSAG;
			$model = $this->_dataExample->create();
			$model->load($customerId, 'idSAG');
			if(count($model->getData())){
				echo "Updated";
			} else {
			   echo "New Added";
			}	
			
			//die("sdsd");
			 /*$saveObj=[
			"idSAG" => '201990',
			"idProduct" => '13',
			"review_rating" => '4',
			"review_text" => 'This Sprite Yoga Strap 8 foot is honest.',
			"reviewer_name" => 'Nikita',
			"lastname" => 'Roy',
			"date_time" => '2017-11-03 13:00:46',
			"review_status" => '0',
			"answer_text" => '',
			"answer_date_time" => '',
			"lang" => 'en']; */
			
			$saveObj=[
		
			"idSAG" => $obj->idSAG,
			"idProduct" => $obj->idProduct,
			"review_rating" => $obj->review_rating,
			"review_text" =>$obj->review_text,
			"reviewer_name" => $obj->reviewer_name,
			"lastname" =>$obj->lastname,
			"date_time" => $obj->date_time,
			"review_status" => $obj->review_status,
			"answer_text" => $obj->answer_text,
			"answer_date_time" => $obj->answer_date_time,
			"lang" => $obj->lang
			
			]; 
			
			echo '<pre>';
			print_r($saveObj);
			$model->addData($saveObj); 

        $saveData = $model->save();  
		if($saveData){
            $this->messageManager->addSuccess( __('Insert Record Successfully !') );
        }
		return $resultRedirect; 
		return $jsonObj; 
		//$quoteModel->save();
		
		
		$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
		$resource = $objectManager->get('Magento\Framework\App\ResourceConnection');
		$connection = $resource->getConnection();
		$tableName = $resource->getTableName('sag_review');

		//Select Data from table
		$sql = "Select * FROM " . $tableName;
		$result = $connection->fetchAll($sql);
		echo '<br>Resullt</br>-------<br>';
		echo '<pre>';print_r($result);
		}
		
	}
	
	// Verify a token
	function tokenCheck($token) {
		$apiKey= urlencode($this->yourFunctionToGetApiKeyFromConfiguration());
		$url= $this->ApiEndPoint. "checkToken.php?token=". urlencode($token). "&apiKey=". $apiKey; 
		$ch= curl_init ($url);
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
	//print_R ($_REQUEST); die('--ok--');
		//print_R ($storeCode); 
		$storeCode= (isset ($_REQUEST["lang"])? $_REQUEST["lang"]:'en');
		
		if($storeCode == 'en'){
			$apikey = $this->helperData->EnglishApi(); 
			
		}else if ($storeCode == 'fr') {
			$apikey = $this->helperData->FrenchApi(); 
			
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
	
	function getStoreId(){
		$ApiKey= $this->yourFunctionToGetApiKeyFromConfiguration();
		list($shopId)= explode('/', $ApiKey);
		return $shopId;	
	}
}