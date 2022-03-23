<?php
namespace Mageplaza\SteavisGarantis\Block\Adminhtml\System\Config\Form\Field;
echo '<style>tr#row_sagwidget_jswidget_createaccount td.value { padding-top: 0;}</style>';
class Link extends \Magento\Config\Block\System\Config\Form\Field
{
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        array $data = []
    ) {
        parent::__construct($context, $data);
    }

    public function render(\Magento\Framework\Data\Form\Element\AbstractElement $element)
    {
        $element->unsScope()->unsCanUseWebsiteValue()->unsCanUseDefaultValue();
        return parent::render($element);
    }

    protected function _getElementHtml(\Magento\Framework\Data\Form\Element\AbstractElement $element)
    {
        return sprintf(
            'If not then please <a href ="https://www.societe-des-avis-garantis.fr/wp-login.php?action=register" target="_blank">Create Account</a>',
            rtrim($this->_urlBuilder->getUrl('custom/module/link'), '/'), //You need to change this link (Use your grid link like 'sales/order/index' will redirect you on order grid page.)
            __('Marketo Logs')
        );
    }
}
