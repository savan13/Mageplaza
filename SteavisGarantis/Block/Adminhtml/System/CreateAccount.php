<?php

namespace Mageplaza\SteavisGarantis\Block\Adminhtml\System;

use Magento\Framework\View\Element\AbstractBlock;
use Magento\Config\Model\Config\CommentInterface;

class CreateAccount extends AbstractBlock implements CommentInterface
{
    public function getCommentText($elementValue)
    {
        $url = $this->_urlBuilder->getUrl('https://www.societe-des-avis-garantis.fr/wp-admin');
        return "This is a <a href='$url'>Dynamically</a> Generated Comment";
	}
}