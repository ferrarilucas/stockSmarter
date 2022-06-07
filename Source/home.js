const home = (function(){

    const row = getUserData()


    return {
        init:function(){
           if(row['Type'] === "ADM"  ){
                document.getElementById('store').classList.remove('d-none')
           }
        },
    }



})()


