/**
 * A set of helpers used by all pages
 * @author Dean Clow <deanrclow@gmail.com>
 */

Helpers = function() {
    /**
     * Clear a form
     * @returns void
     */
    this.clearForm = function() {
        $("form")[0].reset();
    };
};
var Helpers = new Helpers();