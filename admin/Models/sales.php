<?php
require_once(__DIR__ . '/../../conf/conf.php');


class Sales extends Conf {
    public $id;
    public $sales_name;
    public $sale;
    public $seller;
    public $date;
    public $customer;
    public $amout;
    public $product;
    public $discount;
    public $total;
    


    public function create(){
        $query = "INSERT INTO sales (salesName, sale, seller, date, customer, amout, product, discount, total) VALUES (:salesName, :sale, :seller, :date, :customer, :amout, :product, :discount, :total)";
        $params = [
            ':salesName' => $this->sales_name,
            ':sale' => $this->sale,
            ':seller' => $this->seller,
            ':date' => $this->date,
            ':customer' => $this->customer,
            ':amout' => $this->amout,
            ':product' => $this->product,
            ':discount' => $this->discount,
            ':total' => $this->total
        ];

        return $this->exec_query($query, $params);
    }

    public function get_sales_by_id($id){
        $query = "SELECT id, salesrName, sale, seller, date, customer, amout, product, discount, total FROM sales WHERE id = :id";
        $params = [':id' => $id];

        $result = $this->exec_query($query, $params);

        if ($result){
            return $result->fetch(PDO::FETCH_ASSOC);
        } else {
            return [];
        }
    }

    public function list_sales(){
        $query = "SELECT id, salesName, sale, seller, date, customer, amout, product, discount, total, created_at, updated_at FROM sales";

        $result = $this->exec_query($query);

        if ($result){
            return $result->fetchAll(PDO::FETCH_ASSOC);
        } else {
            return [];
        }
    }

    public function update($id){
        $query = "UPDATE sales SET
            salesName = :salesName,
            sale = :sale,
            seller = :seller,
            date = :date,
            customer = :customer
            amout = :amout,
            product = :product,
            discount = :discount,
            total = :total
            WHERE id = :id";

        $params = [
            ':id' => $id,
            ':salesName' => $this->sales_name,
            ':sale' => $this->sale,
            ':seller' => $this->seller,
            ':date' => $this->date,
            ':customer' => $this->customer,
            ':amout' => $this->amout,
            ':product' => $this->product,
            ':discount' => $this->discount,
            ':total' => $this->total
        ];

        return $this->exec_query($query,$params);
    }

    public function delete($id){
        $query = "DELETE FROM sales WHERE id = :id";
        $params = [':id' => $id];

        return $this->exec_query($query,$params);
    }

    public function checkSales($id = null){
        $query = "SELECT COUNT(*) as total FROM sales WHERE salesName = :salesName";
        $params = [':salesName' => $this->sales_name];

        if ($id){
            $query.= " AND id != :id";
            $params[':id'] = $id;
        }

        $result = $this->exec_query($query, $params);

        if ($result){
            return $result->fetch(PDO::FETCH_ASSOC)['total'];
        } else {
            return 0;
        }

    }
}