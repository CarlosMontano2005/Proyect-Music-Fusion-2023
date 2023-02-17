  function mostrar(){

    swal({
        title: "Estas seguro de que deseas eliminar los registros?",
        icon: "warning",
        buttons: true,
        dangerMode: true,
      })
      .then((willDelete) => {
        if (willDelete) {
          swal("Los registros se han eliminado exitosamente", {
            icon: "success",
          });
        } else {
          swal("Accion cancelada", {
            icon: "error",
          });
        }
      });
  }