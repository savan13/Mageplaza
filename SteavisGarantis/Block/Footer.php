<?php

namespace Mageplaza\SteavisGarantis\Block;

class Footer extends \Magento\Framework\View\Element\Html\Link

{

protected $_template = 'Mageplaza_SteavisGarantis::copyright.phtml';

public function getHref()

{

return__( 'testuser');

}

public function getLabel()

{

return __('Test Link');

}

}

?>
