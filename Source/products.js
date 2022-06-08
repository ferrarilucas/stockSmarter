const products = (() => {
    const user = getUserData();

    function listProducts(){
        api.post(`productList.php`,{
            I:user.Id
        })
        .then((response) => {
            if(CheckResponse(response)){
                const products = response.data.Products
                let html = ''

                products.forEach((key,i) => {
                    html += `<tr style='margin: auto; justify-content: center; border: 1px solid grey; border-radius: 5px;'>
                               <td><a href="javascript:products.productEdit(${products[i].Id})">${products[i].Id}</a></td>
                               <td><a href="javascript:products.productEdit(${products[i].Id})">${products[i].Name}</a></td>
                               <td><a href="javascript:products.productEdit(${products[i].Id})">${products[i].StoreName}</a></td>
                               <td><a href="javascript:products.productEdit(${products[i].Id})">${products[i].Qtd}</a></td>
                             </tr>`
                })
                document.getElementById('product-list').innerHTML = html
            }
        })

    }

    function saveProduct(id){
      const name =  document.getElementById('productName').value
      const store =  $('#store').val()
      const qtd = document.getElementById('qtd').value
      let productId = typeof id !== 'undefined' || id > 0 ? id : 0

      api.post(`createProduct.php`,{
        name:name,
        store:store,
        qtd:qtd,
        id:productId,
      })
      .then((response) => {
        if(CheckResponse(response)){
          $('#productEdit').modal('hide')
          listProducts()
        }
        else{
          alert(response.data.Status)
        }
      })

    }

    return {
        init:function(){
            listProducts();

        },

        productEdit:function(productId){

            let dlg = document.getElementById('productEdit')
            let isEdit = typeof productId !== 'undefined'

            if( dlg == null){
                dlg = `<div class="modal fade" id="productEdit" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                         <div class="modal-dialog" role="document">
                           <div class="modal-content">
                             <div class="modal-header">
                               <h5 class="modal-title" id="exampleModalLabel">${isEdit?`Editar`:`Adicionar`} produto</h5>
                               <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                 <span aria-hidden="true">&times;</span>
                               </button>
                             </div>
                             <div class="modal-body">
                               <form>
                                 <div class="form-group" id="idLabel" class="d-none">
                                   <label for="recipient-Id" class="col-form-label">ID</label>
                                   <i id="productId"></i>
                                 </div>
                                 <div class="form-group">
                                   <label for="recipient-name" class="col-form-label">Nome</label>
                                   <input type="text" name="name" class="form-control" id="productName" required>
                                 </div>
                                 <div class="form-group">
                                    <label for="message-text" class="col-form-label">loja: </label>
                                    <select id="store" class="form-control" type="text" name="store" required></select>
                                 </div>
                                 <div class="form-group">
                                   <label for="message-text" class="col-form-label">Quantidade: </label>
                                   <input class="form-control" type="number" name="qtd" id="qtd"></input>
                                 </div>
                               </form>
                             </div>
                             <div class="modal-footer">
                               <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                               <button type="submit" class="btn btn-primary" id="send">Salvar</button>
                             </div>
                           </div>
                         </div>
                       </div>`

              document.querySelector('body').innerHTML += dlg;

               $('#productEdit').ready(function($){
                    $('#send').on('click', e=>{
                      saveProduct(isEdit?productId:0)
                   })
              });

               $('#store').multiselect()

               $('#productEdit').on('hide.bs.modal', function (event) {
                     $('#productEdit').remove()
                })
            }
             api.post(`storeList.php`,{
                I:user.Id
            })
            .then((response) => {
                if(CheckResponse(response)){
                    const store = response.data.Store
                    let html = ''

                    store.forEach((key,i) => {
                        html += `<option value="${store[i].Id}">${store[i].Name}</option>`
                    })
                    document.getElementById('store').innerHTML = html

                    if(isEdit){
                        api.post(`productList.php`,{
                            I:user.Id,
                            product: productId
                        }).then((response) => {
                            if(CheckResponse(response)){
                                let product = response.data.Products[0]
                                document.getElementById('idLabel').classList.remove('d-none')
                                document.getElementById('productId').innerHTML = product.Id
                                document.getElementById('productName').value = product.Name
                                document.getElementById('store').value = product.StoreId
                                document.getElementById('qtd').value = product.Qtd

                            }
                        })
                    }else{
                        document.getElementById('idLabel').classList.add('d-none')
                        document.getElementById('productId').innerHTML = ''
                        document.getElementById('productName').value = ''
                        document.getElementById('qtd').value = ''

                    }

                    $('#productEdit').modal('show')

                }
            })

        }

    }

})();