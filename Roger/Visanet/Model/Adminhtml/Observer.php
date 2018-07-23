<?php
class Roger_Visanet_Model_Adminhtml_Observer{
    public function addOrderCustomBlock(Varien_Event_Observer $observer){
        $block = $observer->getBlock();
        if(($block->getNameInLayout() == 'order_info') && ($child = $block->getChild('roger.order.info.visanetorderblock'))){
            $transport = $observer->getTransport();
            if($transport){
                $html = $transport->getHtml();
                $html .= $child->toHtml();
                $transport->setHtml($html);
            }
        }
        
    }
}