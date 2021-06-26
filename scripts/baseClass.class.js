export default class baseClass{
	constructor(){}
	validParam(p,n){
		if($.isEmptyObject(p)){
			throw new Error('missing parameter! ('+s+')');
		}
	}
}