<!-- modal agregar -->
<div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="updateModalLabel">Agregar Venta</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="" method="post" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="salesName" class="form-label">Nombre de la Venta: </label>
                        <input type="text" name="salesName" id="salesName" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="sale" class="form-label">Venta:</label>
                        <input type="sale" name="sale" id="sale" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="seller" class="form-label">Vendedor:</label>
                        <input type="text" name="seller" id="seller" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="date" class="form-label">Fecha:</label>
                        <input type="text" name="date" id="date" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="customer" class="form-label">Cliente</label>
                        <input type="text" name="customer" id="customer" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="amout" class="form-label">Cantidad</label>
                        <input type="text" name="amout" id="amout" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="product" class="form-label">Producto</label>
                        <input type="text" name="product" id="product" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="discount" class="form-label">Descuento</label>
                        <input type="text" name="discount" id="discount" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="total" class="form-label">Total</label>
                        <input type="text" name="total" id="total" class="form-control" required>
                    </div>
                    
                    <span class="text-danger" id="errorMessage"></span>

                    <div class="">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary"><i class="fa-solid fa-floppy-disk"></i> Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>