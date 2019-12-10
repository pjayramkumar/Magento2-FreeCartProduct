<?php

namespace Elightwalk\Freecartproduct\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

class FreeProductRemove implements ObserverInterface {

    public    $helper;
    protected $productRepository;
    protected $_coreSession;
    protected $cart;
    protected $_registry;

    public function __construct(
        \Elightwalk\Freecartproduct\Helper\Data $helper,
        \Magento\Catalog\Api\ProductRepositoryInterface $productRepository,
        \Magento\Framework\Session\SessionManagerInterface $coreSession,
        \Magento\Checkout\Model\Cart $cart,
        \Magento\Framework\Registry $registry
    )
    {
        $this->helper            = $helper;
        $this->productRepository = $productRepository;
        $this->_coreSession      = $coreSession;
        $this->cart              = $cart;
        $this->_registry         = $registry;
    }

    public function execute(Observer $observer)
    {
        $skuFromSystem = $this->helper->getConfig('freecart/general/freeproduct_sku');
        $quoteItem     = $observer->getQuoteItem();
        $skuFromQuote  = $quoteItem->getSku();
        $quote         = $quoteItem->getQuote();
        $this->_coreSession->start();
        $sessionMessage = $this->_coreSession->getFreeProductHistroy();
        $itemId = $quoteItem->getItemId();
        if(!$this->_registry->registry('removeFreeProduct')) {
            if(isset($sessionMessage['product_ids'][$itemId])) {
                $this->_registry->register('removeFreeProduct', $skuFromSystem);
                $freeProdId = $sessionMessage['product_ids'][$itemId];

                if($freeProdId) {
                    $item = $quote->getItemById($freeProdId);
                    if($item) {
                        if($item->getQty() > 1) {
                            $remainedQty = $item->getQty() - 1;
                            $this->cart->updateItem($item->getItemId(), $remainedQty);
                        } else {
                            $this->cart->removeItem($item->getItemId())->save();
                        }
                    }
                }
            }
        }
    }
}
?>