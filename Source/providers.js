const providers = (()=>{
    const user = getUserData();

    function listProviders(){
        api.post(`providersList.php`, {
            I:user.Id
        })
        .then((response)=>{
            if(CheckResponse(response)){
                const provider = response.data.Providers
                let list = ''
                provider.forEach((key,i)=>{
                   list += `<tr>
                              <td>${provider[i].Name}</td>
                              <td>${provider[i].Phone}</td>
                              <td>${provider[i].Description}</td>
                            </tr>`
                })
                document.getElementById('provider-list').innerHTML = list
            }
            else{
                alert(response['Status'], response['Text'])
            }
        })

    }

    function addProvider(){

      let dlg = document.getElementById('providerEdit')

      if( dlg == null){
          dlg = `<div class="modal fade" id="providerEdit" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                             <input type="text" name="name" class="form-control" id="providerName" required>
                           </div>
                           <div class="form-group">
                             <label for="message-text" class="col-form-label">Telefone</label>
                             <input class="form-control" type="phone" name="phone" id="phone" required></input>
                           </div>
                           <div class="form-group">
                             <label for="message-text" class="col-form-label">CNPJ</label>
                             <input class="form-control" type="text" name="cnpj" id="cnpj"></input>
                           </div>
                           <div class="form-group">
                             <label for="message-text" class="col-form-label">descrição:</label>
                             <textarea class="form-control" id="description"></textarea>
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
      }
      document.getElementById('providerName').value  = ''
      document.getElementById('phone').value  = ''
      document.getElementById('cnpj').value = ''
      document.getElementById('description').value = ''

      $('#providerEdit').modal('show')
      $('#providerEdit').ready(function($){
        $('#send').on('click', e=>{saveProvider()})
      });
      document.querySelector('#createProvider').addEventListener('click', addProvider)
    }

    function saveProvider(){
        let name = document.getElementById('providerName').value
        let phone = document.getElementById('phone').value
        let cnpj = document.getElementById('cnpj').value
        let description = document.getElementById('description').value

        api.post(`createProvider.php`,{
            I:user.Id,
            Name: name,
            Phone: phone,
            Cnpj: cnpj,
            Description: description
        })
        .then((response)=>{
            if(CheckResponse(response)){
                alert('Fornecedor adicionado com sucesso')
                $('#providerEdit').modal('hide')
                listProviders()
            }
            else{
                alert(response.Text)
            }
        })
    }

    return {
        init:function(){

            document.querySelector('#createProvider').addEventListener('click', addProvider)

            listProviders()
        }
    }

})()