<?php
// app/code/local/Envato/Custompaymentmethod/Block/Info/Custompaymentmethod.php
class Roger_Visanet_Block_Info_Visanet extends \Magento\Framework\View\Element\Template
{
  protected function _prepareSpecificInformation($transport = null)
  {
    if (null !== $this->_paymentSpecificInformation) 
    {
      return $this->_paymentSpecificInformation;
    }
     
    $data = array();
    if ($this->getInfo()->getCustomFieldOne()) 
    {
      //$data[Mage::helper('payment')->__('Custom Field One')] = $this->getInfo()->getCustomFieldOne();
    }
     
    if ($this->getInfo()->getCustomFieldTwo()) 
    {
      //$data[Mage::helper('payment')->__('Custom Field Two')] = $this->getInfo()->getCustomFieldTwo();
    }
 
    $transport = parent::_prepareSpecificInformation($transport);
     
    return $transport->setData(array_merge($data, $transport->getData()));
  }
}