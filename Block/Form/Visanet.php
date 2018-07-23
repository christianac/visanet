<?php
// app/code/local/Envato/Custompaymentmethod/Block/Form/Custompaymentmethod.php
class Roger_Visanet_Block_Form_Visanet extends \Magento\Framework\View\Element\Template
{
  protected function _construct()
  {
    parent::_construct();
    $this->setTemplate('visanet/form/visanet.phtml');
  }
}