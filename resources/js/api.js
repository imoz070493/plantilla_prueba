import axios from "axios";

export function getUrl(url, paginando){
	/*console.log(url)
	console.log(paginando)*/
	if(paginando){
		return axios.get(url);
	}else{
		return axios.get("api"+url);
	}
}

export function postUrl(url, parametros){
	if(parametros.id){
		return axios.put("api"+url,parametros);
	}else{
		return axios.post("api"+url,parametros);
	}
}

export function deleteUrl(url, parametros){
	return axios.delete("api"+url,parametros);
}