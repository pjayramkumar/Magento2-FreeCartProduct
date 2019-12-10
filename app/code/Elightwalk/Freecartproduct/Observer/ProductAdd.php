<?php
namespace Elightwalk\Freecartproduct\Observer;

use Magento\Framework\Event\ObserverInterface;

class ProductAdd implements ObserverInterface {

    protected $productRepository;
    public    $helper;
    protected $_registry;
    protected $session;
    protected $cart;

    public function __construct(
        \Magento\Catalog\Api\ProductRepositoryInterface $productRepository,
        \Elightwalk\Freecartproduct\Helper\Data $helper,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Session\SessionManagerInterface $session,
        \Magento\Checkout\Model\Cart $cart
    )
    {
        $this->productRepository = $productRepository;
        $this->helper            = $helper;
        $this->_registry         = $registry;
        $this->session           = $session;
        $this->cart              = $cart;
    }

    public function execute(\Magento\Framework\Event\Observer $observer) {
        $item        = $observer->getEvent()->getData('quote_item');			
        $item        = ($item->getParentItem() ? $item->getParentItem() : $item);
        $pId         = $observer->getEvent()->getProduct();
        $defaultPro  = $this->productRepository->get($pId->getSku());
        if(!$this->_registry->registry('addFreeProduct')) {
            if($defaultPro->getFreeproductAttribute()) {
                $sku       = $this->helper->getConfig('freecart/general/freeproduct_sku');
                $_product  = $this->productRepository->get($sku);
                $productId = $_product->getId();

                $params = array(
                    'product' => $productId, 
                    'qty'     => 1
                );

                $this->_registry->register('addFreeProduct', $sku);
                $this->cart->addProduct($_product, $params);
                $this->cart->save();
                $productInfo = $this->cart->getQuote()->getItemsCollection();
                foreach($productInfo as $data) {
                    if($data->getSku() == $sku) {
                        $quoteItemId = $data->getItemId();
                        $freeProductHistroy = $this->session->getFreeProductHistroy();
                        if(is_array($freeProductHistroy)) {
                            $productIds = $freeProductHistroy['product_ids'];
                            $productIds[$item->getItemId()] = $quoteItemId;
                        } else {
                            $productIds = array($item->getItemId() => $quoteItemId);
                        }
                        $sessionArray = array("product_ids" => $productIds);
                        $this->session->setFreeProductHistroy($sessionArray);
                    }
                }
            }
        }
    }
}
?>