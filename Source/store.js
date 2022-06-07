const store = (()=>{
    const user = getUserData()

    function addStore(){
        let dlg = document.getElementById('StoreEdit')

      if( dlg == null){
          dlg = `<div class="modal fade" id="StoreEdit" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                   <div class="modal-dialog" role="document">
                     <div class="modal-content">
                       <div class="modal-header">
                         <h5 class="modal-title" id="exampleModalLabel">adicionar Fornecedor</h5>
                         <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                           <span aria-hidden="true">&times;</span>
                         </button>
                       </div>
                       <div class="modal-body">
                         <form>
                           <div class="form-group">
                             <label for="recipient-name" class="col-form-label">Nome</label>
                             <input type="text" name="name" class="form-control" id="storeName" required>
                           </div>
                             <label for="message-text" class="col-form-label">CNPJ</label>
                             <input class="form-control" type="text" name="cnpj" id="cnpj"></input>
                           </div>
                         </form>
                       <div class="modal-footer">
                         <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                         <button type="submit" class="btn btn-primary" id="send">Salvar</button>
                       </div>
                     </div>
                   </div>
                 </div>`

        document.querySelector('body').innerHTML += dlg;
      }

    document.getElementById('storeName').value = ''
    document.getElementById('cnpj').value = ''
    $('#StoreEdit').modal('show')
    $('#StoreEdit').ready(function($){
      $('#send').on('click', e=>{saveStore()})
    });
    document.getElementById('addStore').addEventListener('click', addStore)
    }

    function saveStore(){
        let name = document.getElementById('storeName').value
        let cnpj = document.getElementById('cnpj').value

        api.post(`createStore.php`,{
            I:user.Id,
            Name: name,
            Cnpj: cnpj,
        })
        .then((response)=>{
            if(CheckResponse(response)){
                alert('Loja adicionada com sucesso')
                $('#StoreEdit').modal('hide')
                listStore()
            }
            else{
                alert(response.data.Text)
            }
        })

    }

    function listStore(){
        api.post(`storeList.php`,{
            I:user.Id
        })
        .then((response) => {
            if(CheckResponse(response)){
                const store = response.data.Store
                let html = ''

                store.forEach((key,i) => {
                    html += `<tr style='margin: auto; justify-content: center; border: 1px solid grey; border-radius: 5px;'>
                               <td>${store[i].Name}</td>
                               <td>${store[i].Id}</td>
                               <td>${store[i].Cnpj}</td>
                             </tr>`
                })
                document.getElementById('store-list').innerHTML = html
            }
        })
    }


    return {
        init:function(){
            document.getElementById('addStore').addEventListener('click', addStore)

            listStore()

        }

    }



})()