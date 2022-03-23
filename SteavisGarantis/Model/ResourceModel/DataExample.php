<?php 
namespace Mageplaza\SteavisGarantis\Model\ResourceModel;
class DataExample extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb{
 public function _construct(){
 $this->_init("sag_review","id");
 }
}
 ?>