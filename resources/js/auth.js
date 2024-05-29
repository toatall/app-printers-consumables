export class Auth {

    constructor(roles) {
        this.roles = roles
    }

    can (...roles) {        
        for(let i=0; i<roles.length; i++) {
            if (this.roles.indexOf(roles[i]) >= 0) {
                return true;
            }
        }
        return false;
    }    

}