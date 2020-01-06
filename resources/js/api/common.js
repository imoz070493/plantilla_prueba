//import request from "request";


import request from '@/utils/request'


export function getUrl(url, query) {
	if (query) {
		return request({
			url: url,
			method: 'get',
			params: query
		});
	} else {
		return request({
			url: url,
			method: 'get'
		});
	}
}

export function postUrl(url, parametros) {
	if (parametros && parametros.id) {
		return request({
			url: url,
			method: 'put',
			params: parametros
		});
	} else {
		return request({
			url: url,
			method: 'post',
			params: parametros
		});
	}
}

export function deleteUrl(url, parametros) {
	if (parametros) {
		return request({
			url: url,
			method: 'delete',
			params: parametros
		});
	} else {
		return request({
			url: url,
			method: 'delete'
		});
	}
}

export function download(url) {
	window.location.assign(url);
}