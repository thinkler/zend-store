<?php

namespace Cart\Factory;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Session\Container;
use Cart\Controller\Plugin\Cart;

class CartFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $cart = new Cart();
        $cart->setSession(new Container('cart'));
        $cart->setSession(new Container('cart_ready'));
        return $cart;
    }
}
