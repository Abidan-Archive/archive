/*global grecaptcha, grecaptcha_sitekey */
/**
 * Run Run recaptchav3
 * @param {string} action The action to match in the controller
 * @param {(token: string) => void} the callback to run after getting a token
 * @return void
 */
export default function recaptcha(action, callback) {
    grecaptcha.ready(() => {
        return grecaptcha
            .execute(grecaptcha_sitekey, { action })
            .then(callback);
    });
}
