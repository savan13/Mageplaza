<?php 
namespace Mageplaza\SteavisGarantis\Model\ResourceModel\DataExample;
class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection{
	public function _construct(){
		$this->_init("Mageplaza\SteavisGarantis\Model\DataExample","Mageplaza\SteavisGarantis\Model\ResourceModel\DataExample");
	}
}
 ?>