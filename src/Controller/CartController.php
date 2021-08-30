<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Cart;
use App\Storage\CartSessionStorage;
use App\Entity\Product;

class CartController extends AbstractController
{
    private $_cartSessionStorage;

    public function __construct(CartSessionStorage $cartSessionStorage){
        $this->_cartSessionStorage = $cartSessionStorage;
    }

    /**
     * @Route("/api/cart", name="api.cart", methods={"GET"})
     */
    public function getCart(): Response {
        $cart=$this->_cartSessionStorage->getCart();

        return $this->json($cart);
    }
    
    /**
     * @Route("/checkout/cart", name="cart_checkout", methods={"GET"})
     */
    public function index(): Response
    {
        $cart=$this->_cartSessionStorage->getCart();

        return $this->render('cart/index.html.twig', [
            'cart' => $cart
        ]);
    }

    /**
     * @Route("/checkout/clear", name="cart_checkout_clear", methods={"GET"})
     */
    public function clearCart(): Response
    {
        $cart=$this->_cartSessionStorage->getCart();
        $cart->removeItems();
        $this->_cartSessionStorage->setCart($cart);

        return $this->redirectToRoute('cart_checkout');
    }

    /**
     * @Route("/checkout/remove/{url}", name="cart_checkout_remove", methods={"GET"})
     */
    public function removeItem(string $url): Response
    {
        $cart=$this->_cartSessionStorage->getCart();

        $cart->removeItemByUrl($url);
        
        $this->_cartSessionStorage->setCart($cart);

        return $this->redirectToRoute('cart_checkout');
    }

    /**
     * @Route("/checkout/change", name="cart_checkout_change", methods={"POST"})
     */
    public function changeQuantity(Request $request): Response
    {
        $cart=$this->_cartSessionStorage->getCart();
        
        $cart->changeQuantity($request->get('url'), $request->get('quantity'));

        $this->_cartSessionStorage->setCart($cart);

        return $this->redirectToRoute('cart_checkout');
    }

    /**
     * @Route("/checkout/shipping", name="cart_shipping")
     */
    public function shipping(): Response{

        $cart=$this->_cartSessionStorage->getCart();

        return $this->render('cart/shipping.html.twig', [
            'cart' => $cart
        ]);
    }
}
