import { request } from '@/utils/request'

// 登录
export function getLogin(form) {
    let res = request('/admin/auth/login', 'POST', form)
    return res
}
// 用户信息
export function getUser(token) {
    let res = request('/admin/auth/user', 'POST', token)
    return res
}
