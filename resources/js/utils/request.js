import axios from 'axios'
import { getToken } from '@/utils/auth'
// create an axios instance
const service = axios.create({
  baseURL: '/api',
  timeout: 5000 // request timeout
})

// request interceptor
service.interceptors.request.use(
  config => {
    /*
    login
    */
    /*
     if (store.getters.token) {
       config.headers['Authorization'] = 'Bearer ' + getToken()
     }
     */
    return config
  },
  error => {
    console.log('error w', error) // for debug
    return Promise.reject(error)
  }
)

// response interceptor
service.interceptors.response.use(
  /**
   * If you want to get http information such as headers or status
   * Please return  response => response
  */

  /**
   * Determine the request status by custom code
   * Here is just an example
   * You can also judge the status by HTTP Status Code
   */
  response => {
    const res = response.data

    // if the custom code is not 20000, it is judged as an error.

    /**
     * control para acceso a api rest laravel
     */
    if (res.message) {
      return res;
    }
    if (res.error) {
      return Promise.reject(new Error(res || 'Error'))
    }
    return response;
  },
  error => {
    /**
       * control para acceso a api rest laravel
       */
    if (error.request) {
      if (error.request.status === 401) {
        if (error.request.responseURL === '/user/login') {
          //console.log('error 401 /user/login', error.request)
        } else {

          return Promise.reject(
            () => {
              console.log('ok')
            }
          )

          //console.log('error 401', error.request.responseURL, process.env.VUE_APP_BASE_API + 'user/url')
        }
        const res = error.response.data
        return Promise.reject(new Error(res.message || 'Error'))
      } else if (error.request.status === 501) {
        //console.log('error 501')
        const res = error.response.data
        return Promise.reject(new Error(res.message || 'Error'))
      } else {
        //console.log('error xyz=' + error.request.status, error)
        //return Promise.reject(new Error(error.request || 'Error'))
      }
    }
    /**
          * fin api rest laravel
          */
    //console.log('error general', error) // for debug
    if (error.response && error.response.data) {

      return Promise.reject(error.response.data)
    }
    else {

      return Promise.reject(error.response)
    }
  }
)

export default service
