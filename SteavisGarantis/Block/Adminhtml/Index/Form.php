<?php
namespace Mageplaza\SteavisGarantis\Block\Adminhtml\Index;

use Magento\Framework\Data\Form\FormKey;
use Magento\Framework\View\Element\Template;

class Form extends Template
{
    protected $_backendUrl;

    protected $formKey;

    /**
     * Form constructor.
     * @param Template\Context $context
     * @param array $data
     * @param FormKey $formKey
     */
    public function __construct(
        Template\Context $context,
        array $data = [],
        FormKey $formKey
    )
    {
        $this->formKey = $formKey;
        parent::__construct($context, $data);
    }

    /**
     * @return string
     */
    public function getFormKey()
    {
        return $this->formKey->getFormKey();
    }
}