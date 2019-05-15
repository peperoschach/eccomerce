<?php

namespace Peperoschach\Model;

use Peperoschach\DB\Sql;
use Peperoschach\Model;
use Peperoschach\Model\User;
use Peperoschach\Model\Product;

class Cart extends Model {

    const SESSION = "Cart";

    public static function getFromSession()
    {
        $cart = new Cart();

        if (isset($_SESSION[Cart::SESSION]) && (int)$_SESSION[Cart::SESSION]['idcart'] > 0)
        {
            $cart->get((int)$_SESSION[Cart::SESSION]['idcart']);
        }
        else
        {
            $cart->getFromSessionID();
        }
    }

    public function get(int $idcart)
    {
        $sql = new Sql();

        $results = $sql->select("SELECT * FROM tb_carts WHERE idcart = :idcart", [
            ':idcart'=>$idcart
        ]);

        $this->setData($results[0]);

    }

    public function save()
    {
        $sql = new Sql();

        $results = $sql->select("CALL sp_carts_save(:idcart, :dessessionid, :iduser, :deszipcode, :vlfreight, :nrdays)", [
          ':idcart'=>$this->getidcart(),
          ':dessessionid'=>$this->getsessionid(),
          ':iduser'=>$this->getiduser(),
          ':deszipcode'=>$this->getdeszipcode(),
          ':vlfreight'=>$this->getvlfreight(),
          ':nrdays'=>$this->getnrdays()
        ]);

        $this->setData($results[0]);
    }

}
