$(document).ready(function() {
    getData();

    /* Función para cargar los datos con AJAX */
    function getData() {
        let content = document.getElementById("content");
        let url = "../../Controllers/supplierController.php";

        if ($.fn.DataTable.isDataTable('#supplierTable')) {
            $('#supplierTable').DataTable().destroy();
        }

        let formData = new FormData();
        formData.append('action', 'ListSuppliers');

        fetch(url, {
                method: "POST",
                body: formData,
                cache: "no-store"
            })
            .then(response => response.text())
            .then(html => {
                content.innerHTML = html;

                $('#supplierTable').DataTable({
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
        createModal.querySelector('.modal-body #supplierName').focus()
    })

    createModal.addEventListener('submit', (event) => {
        event.preventDefault();

        let formData = new FormData(event.target);
        formData.append("action", 'Create');

        let url = "../../Controllers/supplierController.php";

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
        createModal.querySelector('.modal-body #supplierName').value = ""
        createModal.querySelector('.modal-body #address').value = ""
        createModal.querySelector('.modal-body #city').value = ""
        createModal.querySelector('.modal-body #email').value = ""
        createModal.querySelector('.modal-body #phone').value = ""
        createModal.querySelector('.modal-body #errorMessage').innerText = "";

    })


    // logica modal actualizar
    updateModal.addEventListener('shown.bs.modal', event => {
        let button = event.relatedTarget;
        let id = button.getAttribute('data-bs-id')

        let inputId = updateModal.querySelector('.modal-body #id')
        let inputSupplierName = updateModal.querySelector('.modal-body #supplierName')
        let inputAddress = updateModal.querySelector('.modal-body #address')
        let inputCity = updateModal.querySelector('.modal-body #city')
        let inputEmail = updateModal.querySelector('.modal-body #email')
        let inputPhone = updateModal.querySelector('.modal-body #phone')

        let action = "GetSupplierById";
        let url = "../../Controllers/supplierController.php"

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
                inputSupplierName.value = data.supplierName || '';
                inputEmail.value = data.email || '';
                inputPhone.value = data.phone || '';
                inputAddress.value = data.address || '';
                inputCity.value = data.city || '';
            }).catch(error => {
                console.error('Error:', error);
            });
    })


    updateModal.addEventListener('submit', event => {
        event.preventDefault();

        let url = "../../Controllers/supplierController.php"
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

        let url = "../../Controllers/supplierController.php";
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