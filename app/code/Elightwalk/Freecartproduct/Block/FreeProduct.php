<?php
namespace Elightwalk\Freecartproduct\Block;

class FreeProduct extends \Magento\Framework\View\Element\Template
{

    protected $_productRepository;
    protected $_registry;

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Catalog\Model\ProductRepository $productRepository,
        \Magento\Framework\Registry $registry, array $data = []
    )
    {
        $this->_productRepository = $productRepository;
        $this->_registry = $registry;
        parent::__construct($context, $data);
    }

    public function freeProductData($sku)
    {
        try {
            return $this->_productRepository->get($sku);
        } catch(Exception $e) {

        }
    }

    public function getCurrentProduct()
    {
        return $this->_registry->registry('current_product');
    } 
}
?>