const users = (function(){
    const user = getUserData();

    function addUser(){

      let dlg = document.getElementById('userEdit')

      if( dlg == null){
          dlg = `<div class="modal fade" id="userEdit" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                   <div class="modal-dialog" role="document">
                     <div class="modal-content">
                       <div class="modal-header">
                         <h5 class="modal-title" id="exampleModalLabel">adicionar Usuario</h5>
                         <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                           <span aria-hidden="true">&times;</span>
                         </button>
                       </div>
                       <div class="modal-body">
                         <form>
                           <div class="form-group">
                             <label for="message-text" class="col-form-label">Tipo de usuario: </label>
                             <select id="type" class="form-control" type="text" name="type" required>
                                ${user['Type'] == 'ADM' ? '<option value="ADM">Administrador</option>' : ''}
                                ${user['Type'] == 'ADM' || user['Type'] == 'Gerente' ? '<option value="Gerente">Gerente</option>' : ''}
                                <option value="Usuario">Funcionario</option>
                             </select>
                           </div>
                           <div class="form-group">
                             <label for="recipient-name" class="col-form-label">Nome</label>
                             <input type="text" name="name" class="form-control" id="recipient-name" required>
                           </div>
                           <div class="form-group">
                             <label for="message-text" class="col-form-label">Email</label>
                             <input class="form-control" type="mail" name="email" id="email" required></input>
                           </div>
                           <div class="form-group">
                             <label for="message-text" class="col-form-label">Senha</label>
                             <input class="form-control" type="password" name="password" id="password" required></input>
                           </div>
                           <div class="form-group">
                             <label for="message-text" class="col-form-label">CPF</label>
                             <input class="form-control" type="text" name="cpf" id="cpf" multiple></input>
                           </div>
                           <div class="form-group">
                             <label for="message-text" class="col-form-label">lojas autorizadas: </label>
                             <select id="store" class="form-control" type="text" name="store" required></select>
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

      api.get(`storeList.php`,{
        I:user.Id
      })
        .then((response) => {
          if(CheckResponse(response)){
            const store = response.data.Store

            document.getElementById('store').innerHTML =''

            store.forEach((key,i) => {
              document.getElementById('store').innerHTML += `<option value="${store[i].Id}">${store[i].Name}</option>`
            })


            $('#userEdit').modal('show')
            $('#userEdit').ready(function($){
              $('#send').on('click', e=>{saveUser()})
            });
            $('#store').multiselect()
            $('#type').multiselect()
          }
        })

        document.querySelector('#addUser').addEventListener('click', addUser)

    }

    //function to save user data from a form with validation
    function saveUser(){
      const name = document.getElementById('recipient-name').value
      const email = document.getElementById('email').value
      const cpf = document.getElementById('cpf').value
      const password = encode(document.getElementById('password').value)
      const store = document.getElementById('store').value
      const storeList = store.split(",")
      const type = document.getElementById('type').value

      if(name.length == 0 || email.length == 0 || cpf.length == 0 || store.length == 0){
        alert('Preencha todos os campos')
        return
      }

      api.post(`createUser.php`,{
        name:name,
        email:email,
        cpf:cpf,
        storelist:storeList,
        p:password,
        t:type,
        e:add
      })
      .then((response) => {
        if(CheckResponse(response)){
          alert('Usuário adicionado com sucesso', 'success')
          $('#userEdit').modal('hide')
          listUsers()
        }
        else{
          alert(response['Status'], response['Text'])
        }
      })
    }

    function listUsers(){
      api.get(`userList.php`,{
        I:user.Id
      })
        .then((response) => {
          if(CheckResponse(response)){
            const user = response.data.User
            let list = ''
            user.forEach((key,i) => {
              list += `<tr style="margin: auto; justify-content: center; text-align: center;">
                         <td style='list-style-type: none; width: 20vh; font-family: Arial;'>${user[i].Name}</td>
                         <td style='list-style-type: none; width: 20vh; font-family: Arial;'>${user[i].Email}</td>
                         <td style='list-style-type: none; width: 20vh; font-family: Arial;'>${user[i].Id}</td>
                       </tr>`
            })
            document.getElementById('users-list').innerHTML = list
          }
        })
    }
    return{
        init:function(){
            const btnAdd = document.querySelector('#addUser')
        //Event Listners
           document.querySelector('#addUser').addEventListener('click', addUser)

        // Population Table
          listUsers()

        }

    }


})()