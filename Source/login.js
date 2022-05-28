const login = (function(){


function submitForm(){

        let login = document.getElementById('login').value
        let pass  = encode(document.getElementById('pass').value)


        api.get(`login.php?l=${login}&p=${pass}`,JSON.stringify({
            headers: {
                'Content-Type': 'application/json'
            }
        }))
        .then((response) => {
            if(CheckResponse(response)){

                let data = {
                    "Loged" : "TRUE",
                    "User" : response.data.User
                }
                data = JSON.stringify(data)
                sessionStorage.setItem('stockSession', encode(data))
                window.location.href = "/stocksmarter/home.html"
            }else{
                console.log(response.data['Text'])
                document.querySelector('body')
                    .innerHTML += `<div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                      <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLongTitle">${response.data['Status']}</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                            </button>
                          </div>
                          <div class="modal-body">
                            ${response.data['Text']}
                          </div>
                          <div class="modal-footer">
                            <button type="button" class="close btn btn-secondary" data-dismiss="modal">Ok</button>
                          </div>
                        </div>
                      </div>
                    </div>`

            $('#exampleModalCenter').modal('show')
            $('#exampleModalCenter .close').on('click', e=>{
            $('#exampleModalCenter').modal('hide')
            return;
        })
            }
        })
        .finally(() =>{ return}/*{ aviso de fim de carregamento }*/)

    }

    return {
        init:function(){
            sessionStorage.removeItem('stockSession')
            document.getElementById('submit').addEventListener('click', e=>{submitForm()})
        },

    }

})();