<?php
class MateusNeves_Checkout_Model_Observer
{
	public function itemPriceChange(){
		$cart = Mage::getSingleton('checkout/session')->getQuote()->getAllItems();
		foreach ($cart as $_item) {
			$itemsPrice = Mage::getSingleton('core/session')->getItemsPrice();
			$itemId = $_item->getProduct()->getId();
			$itemName = $_item->getProduct()->getName();

			if(!$itemsPrice){
				$itemsPrice[$itemId] = $_item->getCalculationPrice();
				Mage::getSingleton('core/session')->setItemsPrice($itemsPrice);
			} else {
				if(array_key_exists($itemId, $itemsPrice)){
					if($itemsPrice[$itemId] != $_item->getCalculationPrice()){
						Mage::getSingleton('core/session')->addNotice('O preço do item "'. $itemName .'" mudou de: '. Mage::helper('checkout')->formatPrice($itemsPrice[$itemId]) .' para: '. Mage::helper('checkout')->formatPrice($_item->getCalculationPrice()).'.');
					}
				}

				$itemsPrice[$_item->getProduct()->getId()] = $_item->getCalculationPrice();
				Mage::getSingleton('core/session')->setItemsPrice($itemsPrice);
			}
		}
	}
}