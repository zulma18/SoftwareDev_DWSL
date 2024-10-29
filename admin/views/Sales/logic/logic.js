$(document).ready(function() {
    getData();

    /* Función para cargar los datos con AJAX */
    function getData() {
        let content = document.getElementById("content");
        let url = "../../Controllers/salesController.php";

        if ($.fn.DataTable.isDataTable('#salesTable')) {
            $('#salesTable').DataTable().destroy();
        }

        let formData = new FormData();
        formData.append('action', 'ListSales');

        fetch(url, {
                method: "POST",
                body: formData,
                cache: "no-store"
            })
            .then(response => response.text())
            .then(html => {
                content.innerHTML = html;

                $('#salesTable').DataTable({
                    pageLength: 25,
                    responsive: true,
                    columnDefs: [{
                        targets: [8],
                        orderable: false,
                        searchable: false
                    },{
                        targets: [7],
                        orderable: false,
                        searchable: false
                    },{
                        targets: [6],
                        orderable: false,
                        searchable: false
                    }]
                });


            })
            .catch(error => {
                console.error("Error fetching data:", error);
            });
    }

    // modales
    let createModal = document.getElementById('createModal')
    let updateModal = document.getElementById('updateModal')
    let deleteModal = document.getElementById('deleteModal')

    // modal agregar
    createModal.addEventListener('shown.bs.modal', event => {
        createModal.querySelector('.modal-body #salesName').focus()
    })

    createModal.addEventListener('submit', (event) => {
        event.preventDefault();

        let formData = new FormData(event.target);
        formData.append("action", 'Create');

        let url = "../../Controllers/salesController.php";

        fetch(url, {
                method: "POST",
                body: formData
            }).then(response => {
                if (response.ok){
                    return response.json();
                } else {
                    throw new Error("Error al obtener los datos");
                }
            })
            .then(data => {
                if (data.status == 'error'){
                    console.log(data)
                    createModal.querySelector('.modal-body #errorMessage').innerText = data.message;
                } else if (data.status == 'success'){
                    createModal.querySelector('.modal-body #errorMessage').innerText = "";

                    var bootstrapModal = bootstrap.Modal.getInstance(createModal);
                    bootstrapModal.hide();

                    getData()
                    showAlert(data.message);
                }
            })
    })

    createModal.addEventListener('hide.bs.modal', event => {
        createModal.querySelector('.modal-body #salesName').value = ""
        createModal.querySelector('.modal-body #sale').value = ""
        createModal.querySelector('.modal-body #seller').value = ""
        createModal.querySelector('.modal-body #date').value = ""
        createModal.querySelector('.modal-body #customer').value = ""
        createModal.querySelector('.modal-body #amout').value = ""
        createModal.querySelector('.modal-body #product').value = ""
        createModal.querySelector('.modal-body #discount').value = ""
        createModal.querySelector('.modal-body #total').value = ""
        createModal.querySelector('.modal-body #errorMessage').innerText = "";

    })


    // logica modal actualizar
    updateModal.addEventListener('shown.bs.modal', event => {
        let button = event.relatedTarget;
        let id = button.getAttribute('data-bs-id')

        let inputId = updateModal.querySelector('.modal-body #id')
        let inputSalesName = updateModal.querySelector('.modal-body #salesName')
        let inputSales = updateModal.querySelector('.modal-body #sale')
        let inputSeller = updateModal.querySelector('.modal-body #seller')
        let inputDate = updateModal.querySelector('.modal-body #date')
        let inputCustomer = updateModal.querySelector('.modal-body #customer')
        let inputAmout = updateModal.querySelector('.modal-body #amout')
        let inputProduct = updateModal.querySelector('.modal-body #product')
        let inputDiscount = updateModal.querySelector('.modal-body #discount')
        let inputTotal = updateModal.querySelector('.modal-body #total')


        let action = "GetSalesById";
        let url = "../../Controllers/salesController.php"

        let formData = new FormData()
        formData.append('id', id)
        formData.append('action', action)

        fetch(url, {
                method: "POST",
                body: formData
            }).then(response => {
                if (response.ok) {
                    return response.json()
                } else {
                    throw new Error('Error al obtener los datos')
                }
            })
            .then(data => {
                inputId.value = data.id || '';
                inputSalesName.value = data.salesName || '';
                inputSale.value = data.sale || '';
                inputSeller.value = data.seller || '';
                inputDate.value = data.date || '';
                inputCustomer.value = data.customer || '';
                inputAmout.value = data.amout || '';
                inputProduct.value = data.product || '';
                inputDiscount.value = data.discount || '';
                inputTotal.value = data.total || '';
            }).catch(error => {
                console.error('Error:', error);
            });
    })


    updateModal.addEventListener('submit', event => {
        event.preventDefault();

        let url = "../../Controllers/salesController.php"
        let formData = new FormData(event.target)
        formData.append('action', 'Update')

        fetch(url, {
                method: "POST",
                body: formData
            }).then(response => {
                if (response.ok) {
                    return response.json()
                } else {
                    throw new Error('Error al obtener los datos')
                }
            })
            .then(data => {
                if (data.status == 'error'){
                    updateModal.querySelector(".modal-body #errorMessage").innerText = data.message;
                    return;
                } else if (data.status == 'success') {
                    updateModal.querySelector(".modal-body #errorMessage").innerText = '';
                    
                    var bootstrapModal = bootstrap.Modal.getInstance(updateModal);
                    bootstrapModal.hide();

                    showAlert(data.message, 'warning');
                    getData()
                }
            }).catch(error => {
                console.error('Error:', error);
            });
    })

    // logica modal eliminar
    deleteModal.addEventListener('shown.bs.modal', event => {
        let button = event.relatedTarget
        let id = button.getAttribute('data-bs-id')
        deleteModal.querySelector('.modal-footer #id').value = id
    })

    deleteModal.addEventListener('submit', event => {
        event.preventDefault();

        let url = "../../Controllers/salesController.php";
        let formData = new FormData(event.target)
        formData.append('action', 'Delete')

        var bootstrapModal = bootstrap.Modal.getInstance(deleteModal);
        bootstrapModal.hide();

        fetch(url, {
            method: "POST",
            body: formData
        }).then(response => {
            if (response.ok) {
                return response.text()
            } else {
                throw new Error('Error al obtener los datos')
            }
        }).then(data => {
            getData();
            showAlert(data,'danger');
        })
    })

    // funcion para mostrar alertas
    function showAlert(message, type='success') {
        $.notify({
            icon: 'icon-bell',
            title: 'Kaiadmin',
            message: `${message}`,
        }, {
            type: type,
            allow_dismiss: true,
            placement: {
                from: "bottom",
                align: "right"
            },
            delay: 4000,
            timer: 1000,
            animate: {
                enter: 'animated fadeInDown',
                exit: 'animated fadeOutUp'
            },
            z_index: 1031
        });

    }
})