<?php


namespace App\Services;
use App\Entity\CartSessionDTO;
use App\Entity\OrderDetail;
use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;


class SessionsHandler
{
    private $session;
    private $em;

    public function __construct(SessionInterface $session, EntityManagerInterface $em)
    {
        $this->session = $session;
        $this->em = $em;
    }

    public function someMethod()
    {
        // stores an attribute in the session for later reuse
        $this->session->set('attribute-name', 'attribute-value');

        // gets an attribute by name
        $foo = $this->session->get('foo');

        // the second argument is the value returned when the attribute doesn't exist
        $filters = $this->session->get('filters', []);

        // ...
    }

    public function RefreshCartItemsSession(Request $request): ?array
    {
        $currentItemsInCart = [];
        $allItems = $request->request->all();
        $i = 0;
        $productId = 0;


        foreach ($allItems as $item) {
            $i++;
            if ($i%2 != 0){
                $productId = (int)$item;
               // $product = ;
                if (!$this->em->getRepository(Product::class)->find($productId)){
                    return null;
                }
            } else {
                $quantity = (int)$item;
                $currentItemsInCart[$productId ] = $quantity;
                if (array_key_exists($productId, $currentItemsInCart)){
                    $currentItemsInCart[$productId] = $quantity;
                }

                $this->session->set('details', $currentItemsInCart);
                $i=0;
            }
        }

        return $currentItemsInCart;

    }

    public function addItemToSession(Request $request):  ?int
    {
        //$this->session->invalidate();
        $product = intval($request->request->get('product'));
        $quantity = intval($request->request->get('quantity'));

        $itemsInCart = null;
        return $this->AssignProductAndQuantitytoSession($product, $quantity);

        // $this->session->invalidate();

    }

    public function addItemToSessionFromCartView(Request $request): ?int
    {
        $data = $request->request->all();
        $quantity = 0;
        $product = 0;
        foreach ($data as $index => $datum) {
            if (str_starts_with($index,'quantity')){
                $quantity = $datum;
            }
            if (str_starts_with($index,'product')){
                $product = $datum;
            }
        }
        return $this->AssignProductAndQuantitytoSession($product, $quantity);
    }

    public function removeItemFromCartSession(int $productId, Request $request): ?int
    {

        $productsInCart = $this->getItemsInCart();
        if ($productsInCart){
            if (array_key_exists($productId, $productsInCart)){
                unset($productsInCart[$productId]);
                if (count($productsInCart) >= 1){
                    $this->session->set('details', $productsInCart);
                } else {
                    $this->session->set('details', []);
                }
                return (count($productsInCart));
            } else {
                return 404;
            }
        } else {
            return 400;
        }
    }



    public function getItemsInCart()
    {
        return $this->session->get('details');
    }

    public function getSessionId(): string
    {
        return $this->session->getId();
    }

    public function invalidateSession()
    {
        return $this->session->invalidate();
    }



    public function AssignProductAndQuantitytoSession(int $product, int $quantity): ?int
    {
        $currentProduct = $this->em->getRepository(Product::class)->find($product);
        if ($currentProduct) {
            if ($this->getItemsInCart() == null) {
                $this->session->set('details', [$product => $quantity]);
            } else {
                $currentDetailsInSession = $this->session->get('details');

                if (array_key_exists($product, $currentDetailsInSession)) {
                    $currentDetailsInSession[$product] = $currentDetailsInSession[$product] + $quantity;
                } else {
                    $currentDetailsInSession[$product] = $quantity;
                }

                $this->session->set('details', $currentDetailsInSession);

            }
            return (count($this->getItemsInCart()));

        } else {
            return null;
        }
    }

}