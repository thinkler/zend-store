<?php

namespace Cart\Controller\Plugin;
use Zend\Mvc\Controller\Plugin\AbstractPlugin;

class Cart extends AbstractPlugin
{
    private $session;

    public function setSession($session)
    {
        $this->session = $session;
    }

    public function setReady($val)
    {
        $this->session['cart_ready'] = $val;
    }

    public function isReady()
    {
        return $this->session['cart_ready'];
    }

    public function insert($item)
    {
        if (! is_array($this->session['cart'])) {
            $this->session['cart'] = array();
        }

        if (empty($this->cartItems()) || $this->isUniq($item)) {
            $this->session['cart'][$item->id] = $item;
        } else {
            $this->update($item->id, $item->quantity);
        }

        return true;
    }

    public function update($id, $qty)
    {
        if (isset($this->session['cart'][$id])) {
            $this->session['cart'][$id]->quantity = $qty;
            return true;
        } else {
            return false;
        }
    }

    public function clean()
    {
        $this->session->offsetUnset('cart');
        return true;
    }

    public function remove($id)
    {
        if (isset($this->session['cart'][$id])) {
            unset($this->session['cart'][$id]);
            return true;
        } else {
            return false;
        }
    }

    public function totalItems()
    {
        $total_items = 0;
        $items = $this->cartItems();
        if (is_array($items) and ! empty($items)) {
            foreach ($items as $item) {
                $total_items = + ($total_items + $item->quantity);
            }
        }
        return (int) $total_items;
    }

    public function totalSum($round = 2, $with_vat = false)
    {
        $sum = 0;
        $items = $this->cartItems();
        if (is_array($items) and ! empty($items)) {
            foreach ($items as $item) {
                $sum = + ($sum + ($item->price * $item->quantity));
            }
        }
        return (float) round($sum, (int) $round);
    }

    public function cartItems()
    {
        $items = $this->session->offsetGet('cart');
        if (empty($items)) {
            return array();
        }
        return $items;
    }

    private function isUniq($newItem)
    {
        foreach ($this->cartItems() as $token => $cartItem) {
            if ($cartItem->id == $newItem->id) { return false; }
        }
        return true;
    }
}
