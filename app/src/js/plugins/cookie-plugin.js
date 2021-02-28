class CookiePlugin
{
    static get(name) {
        let cookies = document.cookie.split(';');

        name += '=';

        for(let i=0; i < cookies.length; i++) {
            while (cookies[i].charAt(0)==' ') {
                cookies[i] = cookies[i].substring(1);
            }

            if (cookies[i].indexOf(name) === 0) {
                return cookies[i].substring(name.length, cookies[i].length);
            }
        }

        return '';
    }
}
