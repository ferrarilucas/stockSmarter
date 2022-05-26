const login = (function(){

    function submitForm(){
        let login = document.getElementById('login').value
        let pass  = document.getElementById('pass').value

        api.post(`login.php?l=${login}&p=${pass}` )
        .then((response) => {
            if(response.data['Status']!='OK'){
                console.log(response.data['Text'])
            }
            else{
                sessionStorage.setItem('stockSession', MD5('TRUE'))
                window.location.href = "/stocksmarter/home.html"
            }

        })
        .catch((err) => {
        console.error("ops! ocorreu um erro" + err);
        });
    }

    return {
        init:function(){

           document.getElementById('submit').addEventListener('click', e=>{submitForm()})
        },

    }

})();