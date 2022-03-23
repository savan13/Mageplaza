<?php

namespace Mageplaza\SteavisGarantis\Model\Backend\Options;

class HorizontalAlignment implements \Magento\Framework\Option\ArrayInterface {

    public function toOptionArray()
    {
        return [['value' => 'left', 'label' => __('Left')], ['value' => 'right', 'label' => __('Right')], ['value' => 'center', 'label' => __('Center')],];
    }

}