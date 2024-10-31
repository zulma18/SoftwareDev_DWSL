<?php
session_start();
if ($_SESSION['user_name'] == "") {
    header("Location: ../../index.php");
    exit();
}
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once('../Models/sale.php');
require_once('../../conf/funciones.php');

$sale = new Sale();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $sale->employee_id = $_SESSION['user_id'];
    $sale->client_id = isset($_POST['customer_id']) ? ($_POST['customer_id']) : '';
    $sale->date = date('Y-m-d H:i:s');
    $sale->total = 0;

    $id = isset($_POST['id']) ? $_POST['id'] : '';

    $action = isset($_POST['action']) ? $_POST['action'] : "";

    if ($action == "ListSales") {
        $result = $sale->list_sales();

        $html = '';

        if (!empty($result)) {
            foreach ($result as $row) {
                $html .= '<tr>';
                $html .= '<td>' . $row['id'] . '</td>';
                $html .= '<td>' . $row['employee'] . '</td>';
                $html .= '<td>' . $row['customer'] . '</td>';
                $html .= '<td>' . $row['total'] . '</td>';
                $html .= '<td>' . $row['date'] . '</td>';
                $html .= '<td>
                    <a href="#" class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#saleDetailsModal" data-bs-id="' . $row['id'] . '"><i class="fa fa-info"></i></a>
                    <a href="#" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal" data-bs-id="' . $row['id'] . '"><i class="fa fa-times"></i></a>
                  </td>';
                $html .= '</tr>';
            }
        } else {
            $html .= '<tr>';
            $html .= '<td colspan="7">Sin resultados</td>';
            $html .= '</tr>';
        }

        echo $html;
        exit();
    }

    if ($action == "GetSaleDetails"){
        $html = "";
        $sale_result = $sale->get_sale_details_id($id);

        if (!empty($sale_result)) {
            foreach ($sale_result as $row) {
                $html .= '<tr>';
                $html .= '<td>' . $row['bookName'] . '</td>';
                $html .= '<td>' . $row['sale_price'] . '</td>';
                $html .= '<td>' . $row['quantity'] . '</td>';
                $html .= '<td>' . $row['subtotal'] . '</td>';
                $html .= '</tr>';
            }
        } else {
            $html .= '<tr>';
            $html .= '<td colspan="7">Sin resultados</td>';
            $html .= '</tr>';
        }
        echo $html;
    }

    if ($action == "GetProducts") {
        $product_list = $sale->list_products();
        echo json_encode($product_list);
    }

    if ($action == "GetProductById"){
        $product_id = isset($_POST['product_id'])? $_POST['product_id'] : '';
        $product_result = $sale->get_product_by_id($product_id);
        echo json_encode($product_result);
    }

    if ($action == "GetClients"){
        $client_list = $sale->list_clients();
        echo json_encode($client_list);
    }

    if ($action == "Create") {
        $saleId = $sale->create();

        if ($saleId){
            $sale->id = $saleId;

            $saleDetails = [];

            $array = $_POST['SaleDetails'];

            for ($i = 0; $i < count($array); $i++){
                $saleDetails[] = [
                    'product_id' => $array[$i]['ProductID'],
                    'quantity' => $array[$i]['Quantity'],
                    'unit_price' => $array[$i]['UnitPrice']
                ];
            }


            if($sale->add_details($saleDetails)){
                header('Content-Type: application/json');
                echo json_encode([
                   'status' =>'success',
                   'message' => 'Venta registrada con éxito'
                ]);
            } else {
                header('Content-Type: application/json');
                echo json_encode([
                   'status' => 'error',
                   'message' => 'Error al registrar los detalles de la venta'
                ]);
            }
        }
    }

    if ($action == "Delete"){
        $result = $sale->delete($id);

        if ($result){
            echo 'Venta eliminada con éxito';
        } else {
            echo 'Error al eliminar la venta';
        }
        exit();
    }
}
