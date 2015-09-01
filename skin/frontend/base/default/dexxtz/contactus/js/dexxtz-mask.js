
/**
 * Copyright [2014] [Dexxtz]
 *
 * @package   Dexxtz_Contactus
 * @author    Dexxtz
 * @license   http://www.apache.org/licenses/LICENSE-2.0
 */	

function maskDexxtz(o, f) {
    v_obj = o
    v_fun = f
    setTimeout('mask()', 1)
}

function mask() {
    v_obj.value = v_fun(v_obj.value)
}

function maskTelephone(v) {
    v = v.replace(/\D/g, "");
    v = v.replace(/^(\d{2})(\d)/g, "($1) $2");
    v = v.replace(/(\d)(\d{4})$/, "$1 - $2");
    return v;
}