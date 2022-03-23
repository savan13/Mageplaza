<?php

namespace Mageplaza\SteavisGarantis\Model\Adminhtml\System\Config\Source;

class HorizontalAlignment implements \Magento\Framework\Option\ArrayInterface {

    public function toOptionArray()
    {
        return [['value' => 'left', 'label' => __('Left')], ['value' => 'right', 'label' => __('Right')]];
    }

}