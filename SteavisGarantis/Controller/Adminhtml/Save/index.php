<?php
//namespace Tym17\AdminSample\Controller\Adminhtml\SampleOne;
namespace Mageplaza\SteavisGarantis\Controller\Adminhtml\Save;
/* namespace Mageplaza\SteavisGarantis\Controller\Index;
use Magento\Framework\Controller\ResultFactory;
 */
class Index extends \Magento\Backend\App\Action
{
  /**
  * Index Action*
  * @return void
  */

	protected $resultRawFactory;

		public function __construct(
			\Magento\Backend\App\Action\Context $context
	        //\Magento\Framework\App\Request\Http $request
		){
			parent::__construct($context);
			//$this->request = $request;
		 }
		 
		public function execute()
		{ 
		$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
		$storeManager = $objectManager->get('\Magento\Store\Model\StoreManagerInterface');
		echo "Store ID:"; 
		echo $storeManager->getStore()->getStoreId();
		echo "<br/>"; 
		$storeCode = $storeManager->getStore()->getCode();
		echo "Store Code:"; 
		echo $storeCode; echo "<br/>"; 
		
		$StoreId = $storeManager->getStore()->getStoreId();
		$storeName = $storeManager->getStore()->getName();
		$storeCode = $storeManager->getStore()->getCode();
		
		echo $storeName; echo "<br/>"; 
		echo $StoreId; echo "<br/>"; 
			// We urlencode*/
			//$siteName = $this->getRequest()->getParam('sitename');
			$email = $this->getRequest()->getParam('email');
			$url = $this->getRequest()->getParam('siteurl'); 
			// Build parameters
			
			$params = "cms=magento&email=$email&url=$url";
			
			// Build createCertificate API Url
			
			if($storeCode == 'fr'){
			$apiUrl = "https://www.societe-des-avis-garantis.fr/wp-content/plugins/ag-core/api/createCertificate.php?" . $params;
			
			}else if ($storeCode == 'en') {
			$apiUrl = "https://www.guaranteed-reviews.com/wp-content/plugins/ag-core/api/createCertificate.php?" . $params;
			}
			else if ($storeCode == 'es') {
			$apiUrl = "https://www.sociedad-de-opiniones-contrastadas.es/wp-content/plugins/ag-core/api/createCertificate.php?" . $params;
			}
			else if ($storeCode == 'it') {
			$apiUrl = "https://www.societa-recensioni-garantite.it/wp-content/plugins/ag-core/api/createCertificate.php?" . $params;
			}
			else if ($storeCode == 'de') {
			$apiUrl = "https://www.g-g-b.de/wp-content/plugins/ag-core/api/createCertificate.php?" . $params;
			}
			else if ($storeCode == 'nl') {
			$apiUrl = "https://www.g-b-n.nl/wp-content/plugins/ag-core/api/createCertificate.php?" . $params;
			}
						
			//$apiUrl = "https://www.societe-des-avis-garantis.fr/wp-content/plugins/ag-core/api/createCertificate.php?" . $params;
			//exit();
			$ch = curl_init ($apiUrl); 
			curl_setopt ($ch,CURLOPT_FOLLOWLOCATION,1); 
			curl_setopt ($ch,CURLOPT_HEADER,0); 
			curl_setopt ($ch,CURLOPT_RETURNTRANSFER,1); 
			curl_setopt ($ch,CURLOPT_SSL_VERIFYHOST,0); 
			curl_setopt ($ch,CURLOPT_SSL_VERIFYPEER,0); 
			$datas = curl_exec ($ch);
			$data = json_decode ($datas,true);
			echo $datas;
			$apiKey = $data["apiKey"];
			//print_r($_POST);
			if($apiKey)
			{
				echo $apiKey;
				
				$password = $data["password"];
				$objectManager = \Magento\Framework\App\ObjectManager::getInstance(); // Instance of object manager
				$resource = $objectManager->get('Magento\Framework\App\ResourceConnection');
				$connection = $resource->getConnection();
				$tableName = $resource->getTableName('sag_user'); //gives table name with prefix
				$data = $connection->fetchAll("SELECT * FROM sag_user where id= 1");
				if(count($data) != 0){
					$sql = "UPDATE " . $tableName . " SET `email`= '$email',`password`= '$password',`siteurl`= '$url',`apikey`= '$apiKey' WHERE id = 1 ";
				}else{
					$sql = "Insert Into " . $tableName . " (email, password, siteurl,apikey) Values ('$email','$password','$url','$apiKey')";
				}
				$connection->query($sql); 
				
				$this->_redirect($this->_redirect->getRefererUrl());
				$this->messageManager->addSuccess( __('Compte créé avec succès') ); 
				
			
			}
			else
				
				echo "API Key not Found";
				/* $this->_redirect($this->_redirect->getRefererUrl());
				$this->messageManager->addError('login utilisateur existant, cet identifiant existe'); */

			// $resultRedirect = $this->resultRedirectFactory->create();
			// $resultRedirect->setPath('adminhtml/system_config/edit/section/sag');
			// return $resultRedirect;
	} 
	
	
}
