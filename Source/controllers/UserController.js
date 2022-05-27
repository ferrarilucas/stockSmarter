class UserController{
    constructor(){
        this.addEventBtns();
    }

    addEventBtns(){
        document.querySelector('.addUser').addEventListener('click', () => {
            document.querySelector('.form-add').style.display = 'flex'
        })

        document.querySelectorAll('.close')[0].addEventListener('click', () => {
            document.querySelector('.form-add').style.display = 'none'
        })

    }
}