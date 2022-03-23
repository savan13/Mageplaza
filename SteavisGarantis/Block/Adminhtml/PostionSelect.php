<?php
 
namespace Mageplaza\SteavisGarantis\Block\Adminhtml;
 
use Magento\User\Model\ResourceModel\User\CollectionFactory as UserCollectionFactory;
 
class PostionSelect implements \Magento\Framework\Option\ArrayInterface
{
    protected $_userFactory;
 
    public function __construct(
        UserCollectionFactory $userFactory
    ) {
        $this->_userFactory = $userFactory;
    }
 
    public function getOptionArray()
    {
        $adminUsers = $this->_userFactory->create();
        $options = [];
        /* foreach ($adminUsers as $adminUser) {
            $options[$adminUser->getId()] = __($adminUser->getUsername());
        }
        return $options; */
        
        // you can add this way also
        $options = [];
        $options[] = __('Footer');
        $options[] = __('Right');
		$options[] = __('Left');
        return $options;
        
    }
 
    public function getAllOptions()
    {
        $res = $this->getOptions();
        array_unshift($res, ['value' => '', 'label' => '']);
        return $res;
    }
 
    public function getOptions()
    {
        $res = [];
        foreach ($this->getOptionArray() as $index => $value) {
            $res[] = ['value' => $index, 'label' => $value];
        }
        return $res;
    }
 
    public function toOptionArray()
    {
        return $this->getOptions();
    }
}
?>