export class Auth {

    constructor(roles) {
        this.roles = roles
    }

    can (...roles) {        
        for(let i=0; i<roles.length; i++) {
            return this.roles.indexOf(roles[i]) !== -1;
        }
    }    

}