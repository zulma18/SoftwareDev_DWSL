<?php
session_start();
if ($_SESSION['user_name'] == "") {
    header("Location: ../../index.php");
    exit();
}
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once('../Models/sales.php');
require_once('../../conf/funciones.php');

$sales = new Sales();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $sales->sales_name = isset($_POST['salesName']) ? $_POST['salesName'] : '';
    $sales->sale = isset($_POST['sale']) ? $_POST['sale'] : '';
    $sales->seller = isset($_POST['seller']) ? $_POST['seller'] : '';
    $sales->date = isset($_POST['date']) ? $_POST['date'] : '';
    $sales->customer= isset($_POST['customer']) ? $_POST['customer'] : '';
    $sales->amout = isset($_POST['amout']) ? $_POST['amout'] : '';
    $sales->product = isset($_POST['product']) ? $_POST['product'] : '';
    $sales->discount = isset($_POST['discount']) ? $_POST['discount'] : '';
    $sales->total= isset($_POST['total']) ? $_POST['total'] : '';

    $id = isset($_POST['id']) ? $_POST['id'] : '';

    $action = isset($_POST['action']) ? $_POST['action'] : "";

    if ($action == "ListSales") {
        $result = $sales->list_sales();

        $html = '';

        if (!empty($result)) {
            foreach ($result as $row) {
                $html .= '<tr>';
                $html .= '<td>' . $row['id'] . '</td>';
                $html .= '<td>' . $row['salesName'] . '</td>';
                $html .= '<td>' . $row['sale'] . '</td>';
                $html .= '<td>' . $row['seller'] . '</td>';
                $html .= '<td>' . $row['date'] . '</td>';
                $html .= '<td>' . $row['customer'] . '</td>';
                $html .= '<td>' . $row['amout'] . '</td>';
                $html .= '<td>' . $row['product'] . '</td>';
                $html .= '<td>' . $row['discount'] . '</td>';
                $html .= '<td>' . $row['total'] . '</td>';
                $html.= '<td>' . $row['created_at'] . '</td>';
                $html.= '<td>' . $row['updated_at'] . '</td>';
                $html .= '<td>
                    <a href="#" class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#updateModal" data-bs-id="' . $row['id'] . '"><i class="fa fa-edit"></i></a>
                    <a href="#" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal" data-bs-id="' . $row['id'] . '"><i class="fa fa-times"></i></a>
                    </td>';
                $html .= '</tr>';
            };
        }

        echo $html;
        exit();
    }

    if ($action == "GetSalesById") {
        $result = $sales->get_sales_by_id($id);

        if (!empty($result)) {
            echo json_encode($result);
        } else {
            echo json_encode([]);
        }
        exit();
    }

    if ($action == "Create") {
        $isSalesRegistered = $sales->checkSales();
        if ($isSalesRegistered > 0) {
            header('Content-Type: application/json');
            echo json_encode([
                'status' => 'error',
                'message' => 'El nombre de la venta ya esta en uso',
                'data' => [
                    'salesName' => $sales->sales_name,
                    'sale' => $sales->sale,
                    'seller' => $sales->seller,
                    'date' => $sales->date,
                    'customer' => $sales->customer,
                    'amout' => $sales->amout,
                    'product' => $sales->product,
                    'discount' => $sales->discount,
                    'total' => $sales->total
                ]
            ]);
            exit();
        } else {
            $result = $sales->create();

            if ($result) {
                echo json_encode([
                    'status' => 'success',
                    'message' => 'Venta creada con exito'
                ]);
            } else {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Error al agregar la venta'
                ]);
            }
        }
        exit();
    }

    if ($action == "Update") {
        $isSalesRegistered = $sales->checkSales($id);
        if ($isSalesRegistered > 0) {
            header('Content-Type: application/json');
            echo json_encode([
                'status' => 'error',
                'message' => 'El nombre de la venta ya esta en uso',
                'data' => [
                    'id' => $id,
                    'salesName' => $sales->sales_name,
                    'sale' => $sales->sale,
                    'seller' => $sales->seller,
                    'date' => $sales->date,
                    'customer' => $sales->customer,
                    'amout' => $sales->amout,
                    'product' => $sales->product,
                    'discount' => $sales->discount,
                    'total' => $sales->total
                ]
            ]);
            exit();
        } else {
            $result = $sales->update($id);

            if ($result) {
                echo json_encode([
                    'status' => 'success',
                    'message' => 'Venta actualizada con exito'
                ]);
            } else {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Error al actualizar la venta'
                ]);
            }
        }
        exit();
    }

    if ($action == "Delete") {
        $result = $sales->delete($id);

        if ($result) {
            echo 'Venta eliminada con exito';
        } else {
            echo 'Error al eliminar la venta';
        }
        exit();
    }
}
